<?php

namespace App\Http\Controllers\Song;

use App\Models\Genre;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Image;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use App\Models\Song;

use Illuminate\Support\Facades\Storage;
use getID3;

use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('user/upload', ['areas' => $areas, 'genres' => $genres]);
    }

    public function uploadSong(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'song_name' => 'required|string',
                'author_id' => 'required|integer',
                'area_id' => 'required|integer',
                'genre_id' => 'required|integer',
                'description' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|string',
                'lyric' => 'nullable|string',
                'audio' => 'required|mimes:mp3,wav,ogg|max:20240', // Max 20MB
            ]);

            $imageData = file_get_contents($request->file('image'));
            $imageName = Str::uuid() . '.webp';
            $image = Storage::disk('public')->put('images/' . $imageName, $imageData);
            $img = Image::create([
                'img_name' => $imageName,
                'img_path' => 'images/' . $imageName,
                'category' => 'song_img',
            ]);
            // Lưu file 
            $audio = $request->file('audio');
            if ($audio) {
                $audioPath = $audio->store('music', 'public'); // Store in 'public/music' folder
            } else {
                return response()->json(['message' => 'No audio file uploaded'], 400);
            }

            // Lấy thời lượng bài hát
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze(storage_path('app/public/' . $audioPath));
            $duration = isset($fileInfo['playtime_string']) ? $fileInfo['playtime_string'] : null; // "mm:ss" format

            // Kiểm tra bài hát có bản quyền không (Tên và file)
            $status = 'published';
            if ($this->isSimilarSongName($request->song_name)) {
                $status = 'pending';
            } elseif ($this->isCopyrightedAudio($audioPath)) {
                $status = 'deleted';
            }

            // Tạo bài hát mới
            $song = new Song();
            $song->song_name = $request->song_name;
            $song->author_id = $request->author_id;
            $song->area_id = $request->area_id;
            $song->genre_id = $request->genre_id;
            $song->description = $request->description;
            $song->audio_path = $audioPath;  // Store the path to audio file
            $song->img_id = $img->id;
            $song->status = $status;
            $song->lyric = $request->lyric;
            $song->duration = $duration;

            // Nếu bài hát được duyệt bản quyền
            if ($status === 'published') {
                // Xử lý DASH
                $dashPath = $this->processDash($audioPath);
                $song->audio_path = $dashPath; // Lưu đường dẫn file manifest
            }

            $song->save();

            // Gửi thông báo cho người dùng
            $message = match ($status) {
                'deleted' => 'Song is copyrighted and marked as deleted.',
                'pending' => 'Song is suspected of copyright infringement, please wait for review',
                default => 'Song uploaded successfully.',
            };
            if ($status === 'deleted') {
                flash()->error('Song is copyrighted and marked as deleted.');
            } else if ($status === 'pending') {
                flash()->warning('Song is suspected of copyright infringement, please wait for review');
            } else {
                flash()->success('Song uploaded successfully.');
            }
            // return response()->json([
            //     'message' => $message,
            //     'song_name' => $song->song_name,
            // ], $status === 'deleted' ? 202 : 201);
            return redirect()->route('profile', Auth::user()->id);
        } catch (\Exception $e) {
            return response()->json(['Lỗi khi upload' => $e->getMessage()], 400);
        }
    }


    private function processDash($audioPath)
    {
        // Chuyển đổi đường dẫn tuyệt đối
        $inputPath = Storage::disk('public')->path($audioPath);
        $outputDir = Storage::disk('public')->path('dash/' . pathinfo($audioPath, PATHINFO_FILENAME));

        // Log::info("Input Path: $inputPath, Output Directory: $outputDir");

        // Tạo thư mục nếu nó chưa tồn tại
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Lệnh ffmpeg
        $command = "ffmpeg -i \"$inputPath\" " .
            "-map 0:a:0 -b:a:0 128k -map 0:a:0 -b:a:1 256k -map 0:a:0 -b:a:2 320k " .
            "-f dash \"$outputDir/output.mpd\"";

        // Ghi log lệnh ffmpeg
        // Log::info("FFmpeg Command: $command");

        exec($command . " 2>&1", $output, $returnCode);

        // Kiểm tra lỗi
        if ($returnCode !== 0) {
            Log::error("FFmpeg Error Output: " . implode("\n", $output));
            throw new \Exception("Error processing DASH. Command: $command. Output: " . implode("\n", $output));
        }

        return 'dash/' . pathinfo($audioPath, PATHINFO_FILENAME) . '/output.mpd';
    }

    private function isCopyrightedAudio(string $filePath): bool
    {
        try {
            // Đường dẫn tuyệt đối tới file nhạc
            $absolutePath = storage_path("app/public/$filePath");
            if (!file_exists($absolutePath)) {
                throw new \Exception("File không tồn tại: $filePath");
            }
    
            // **Trích xuất 200KB đầu của file**
            $fileSize = filesize($absolutePath);
            $chunkSize = 200 * 1024; // 200KB
            $startByte = 0; // Bắt đầu từ đầu file
    
            $handle = fopen($absolutePath, 'rb');
            fseek($handle, $startByte);
            $data = fread($handle, $chunkSize);
            fclose($handle);
    
            // Tạo file tạm từ phần dữ liệu đầu
            $chunkFilePath = storage_path("app/public/temp/first200KB.mp3");
            file_put_contents($chunkFilePath, $data);
    
            // Tạo fingerprint cho phần đầu 200KB
            $outputHead = [];
            exec("fpcalc -json \"$chunkFilePath\"", $outputHead);
            $uploadedFingerprintHead = json_decode(implode('', $outputHead), true);
    
            // Xóa file tạm
            Storage::disk('public')->delete("temp/first200KB.mp3");
    
            if (empty($uploadedFingerprintHead) || !isset($uploadedFingerprintHead['fingerprint'])) {
                throw new \Exception("Không thể tạo fingerprint đoạn đầu: $filePath");
            }
    
            // **Trích xuất 200KB cuối của file**
            $startByte = max(0, $fileSize - $chunkSize); // Bắt đầu từ 200KB cuối
            $handle = fopen($absolutePath, 'rb');
            fseek($handle, $startByte);
            $data = fread($handle, $chunkSize);
            fclose($handle);
    
            // Tạo file tạm từ phần dữ liệu cuối
            $chunkFilePath = storage_path("app/public/temp/last200KB.mp3");
            file_put_contents($chunkFilePath, $data);
    
            // Tạo fingerprint cho phần cuối 200KB
            $outputChunk = [];
            exec("fpcalc -json \"$chunkFilePath\"", $outputChunk);
            $uploadedFingerprintChunk = json_decode(implode('', $outputChunk), true);
    
            // Xóa file tạm
            Storage::disk('public')->delete("temp/last200KB.mp3");
    
            if (empty($uploadedFingerprintChunk) || !isset($uploadedFingerprintChunk['fingerprint'])) {
                throw new \Exception("Không thể tạo fingerprint đoạn cuối: $filePath");
            }
    
            // Lấy danh sách file JSON trong thư mục Copyrighted_music
            $copyrightedFiles = Storage::disk('public')->files('Copyrighted_music');
            if (empty($copyrightedFiles)) {
                Log::warning("Thư mục 'Copyrighted_music' không có file nào.");
                return false;
            }
    
            // So sánh fingerprint
            foreach ($copyrightedFiles as $jsonFile) {
                $jsonContent = Storage::disk('public')->get($jsonFile);
                $copyrightedFingerprint = json_decode($jsonContent, true);
    
                if (empty($copyrightedFingerprint)) {
                    Log::warning("File JSON không hợp lệ: $jsonFile");
                    continue;
                }
    
                // So sánh fingerprint đoạn đầu
                $similarityHead = 0;
                if (isset($copyrightedFingerprint['first_200KB_fingerprint'])) {
                    similar_text(
                        $uploadedFingerprintHead['fingerprint'],
                        $copyrightedFingerprint['first_200KB_fingerprint']['fingerprint'] ?? '',
                        $similarityHead
                    );
                }
    
                // So sánh fingerprint đoạn cuối
                $similarityChunk = 0;
                if (isset($copyrightedFingerprint['last_200KB_fingerprint'])) {
                    similar_text(
                        $uploadedFingerprintChunk['fingerprint'],
                        $copyrightedFingerprint['last_200KB_fingerprint']['fingerprint'] ?? '',
                        $similarityChunk
                    );
                }
    
                Log::info("----------------------------------------------------------------");
                Log::info("So sánh fingerprint: $jsonFile");
                Log::info("Độ tương đồng đoạn đầu: $similarityHead%");
                Log::info("Độ tương đồng đoạn cuối: $similarityChunk%");
    
                // Nếu mức độ tương đồng >= 60% cho bất kỳ loại fingerprint nào, coi như vi phạm bản quyền
                if ($similarityHead >= 60 || $similarityChunk >= 60) {
                    Log::info("File nhạc trùng khớp với bản quyền: $jsonFile");
                    return true;
                }
            }
    
            return false;
        } catch (\Exception $e) {
            Log::error("Lỗi trong isCopyrightedAudio: " . $e->getMessage());
            return false;
        }
    }
    




    private function isSimilarSongName(string $songName): bool
    {
        try {
            // Kiểm tra file tồn tại trên disk 'public'
            if (!Storage::disk('public')->exists('copyrighted_song_names.json')) {
                throw new \Exception("File 'copyrighted_song_names.json' không tồn tại trong public/storage.");
            }

            // Load nội dung file từ disk 'public'
            $fileContent = Storage::disk('public')->get('copyrighted_song_names.json');

            // Giải mã JSON
            $copyrightedSongs = json_decode($fileContent, true);

            // Kiểm tra JSON hợp lệ
            if (!is_array($copyrightedSongs)) {
                throw new \Exception("Nội dung file không phải là một JSON hợp lệ.");
            }

            // Chuẩn hóa tên bài hát để so sánh
            $songName = strtolower(trim($songName));

            // Check similarity
            foreach ($copyrightedSongs as $copyrightedName) {
                $copyrightedName = strtolower(trim($copyrightedName)); // Chuẩn hóa tên bản quyền

                // Tính khoảng cách Levenshtein
                $distance = levenshtein($songName, $copyrightedName);

                // Ngưỡng dựa trên 30% độ dài tên bản quyền
                $threshold = ceil(strlen($copyrightedName) * 0.3);

                // Nếu khoảng cách nhỏ hơn hoặc bằng ngưỡng, coi là tương đồng
                if ($distance <= $threshold) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Lỗi trong isSimilarSongName: " . $e->getMessage());
            return false;
        }
    }


    public function getSong($id)
    {
        $song = Song::find($id);
        if ($song) {
            $absolutePath = url('storage/' . $song->audio_path);
            return response()->json($absolutePath, 200);
        } else {
            return response()->json(['message' => 'Song not found'], 404);
        }
    }

    public function searchSong(Request $request)
    {
        $query = $request->input('query');
        $songs = Song::where('song_name', 'like', '%' . $query . '%')
            ->where('status', 'published') // Thêm điều kiện kiểm tra status
            ->orWhereHas('author', function ($q) use ($query) {
                $q->where('author_name', 'like', '%' . $query . '%');
            })
            ->where('status', 'published') // Cần thêm điều kiện này để áp dụng cho cả orWhereHas
            ->get();

        if (Auth::check()) {
            $playlists = Playlist::where('user_id', auth()->id)->get();
        } else {
            $playlists = [];
        }
        return view('user/searchsong', ['songs' => $songs, 'query' => $query, 'playlists' => $playlists]);
    }

    //Upload nhạc bản quyền để thực hiện chức năng so sánh
    public function uploadAndGenerateFingerprint(Request $request)
    {
        $request->validate([
            'audio_files.*' => 'required|file|mimes:mp3,wav|max:15240', // Tối đa 15MB mỗi file
        ]);

        $files = $request->file('audio_files'); // Nhận danh sách file upload

        foreach ($files as $file) {
            // Lưu file nhạc tạm thời
            $audioPath = $file->store('temp', 'public');
            $absolutePath = storage_path("app/public/$audioPath");
            Log::info("Uploaded file: $absolutePath");

            $chunkSize = 200 * 1024; // 200KB (thay đổi nếu cần)
            $fileSize = filesize($absolutePath);

            // **Trích xuất 200KB đầu**
            $handle = fopen($absolutePath, 'rb');
            $dataFirst = fread($handle, $chunkSize); // Đọc từ đầu file
            fclose($handle);

            $firstChunkFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_first200KB.mp3';
            $firstChunkFilePath = storage_path("app/public/temp/$firstChunkFileName");
            file_put_contents($firstChunkFilePath, $dataFirst);

            // Tạo fingerprint cho đoạn đầu
            $outputFirst = [];
            exec("fpcalc -json \"$firstChunkFilePath\"", $outputFirst);
            $resultFirst = json_decode(implode('', $outputFirst), true);

            // Kiểm tra nếu không tạo được fingerprint cho đoạn đầu
            if (empty($resultFirst)) {
                Storage::disk('public')->delete($audioPath);
                Storage::disk('public')->delete($firstChunkFilePath);
                return response()->json([
                    'error' => "Could not generate fingerprint for the first 200KB of {$file->getClientOriginalName()}",
                ], 500);
            }

            // **Trích xuất 200KB cuối**
            $startByte = max(0, $fileSize - $chunkSize);

            $handle = fopen($absolutePath, 'rb');
            fseek($handle, $startByte); // Di chuyển con trỏ tới 200KB cuối
            $dataLast = fread($handle, $chunkSize);
            fclose($handle);

            $lastChunkFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_last200KB.mp3';
            $lastChunkFilePath = storage_path("app/public/temp/$lastChunkFileName");
            file_put_contents($lastChunkFilePath, $dataLast);

            // Tạo fingerprint cho đoạn cuối
            $outputLast = [];
            exec("fpcalc -json \"$lastChunkFilePath\"", $outputLast);
            $resultLast = json_decode(implode('', $outputLast), true);

            // Kiểm tra nếu không tạo được fingerprint cho đoạn cuối
            if (empty($resultLast)) {
                Storage::disk('public')->delete($audioPath);
                Storage::disk('public')->delete($firstChunkFilePath);
                Storage::disk('public')->delete($lastChunkFilePath);
                return response()->json([
                    'error' => "Could not generate fingerprint for the last 200KB of {$file->getClientOriginalName()}",
                ], 500);
            }

            // Lưu thông tin fingerprint vào JSON
            $jsonFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.json';
            $jsonPath = "Copyrighted_music/$jsonFileName";

            $dataToSave = [
                'first_200KB_fingerprint' => $resultFirst,
                'last_200KB_fingerprint' => $resultLast,
            ];

            Storage::disk('public')->put($jsonPath, json_encode($dataToSave, JSON_PRETTY_PRINT));

            // Xóa file tạm
            Storage::disk('public')->delete($audioPath);
            Storage::disk('public')->delete("temp/$firstChunkFileName");
            Storage::disk('public')->delete("temp/$lastChunkFileName");
        }

        return response()->json([
            'message' => 'Fingerprints for the first and last 200KB generated successfully!',
        ]);
    }
}
