<?php

namespace App\Http\Controllers\Song;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
                'img_id' => 'required|integer',
                'status' => 'required|string',
                'lyric' => 'nullable|string',
                'audio' => 'required|mimes:mp3,wav,ogg|max:20240', // Max 20MB
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
            if ($this->isCopyrightedAudio($audioPath)) {
                $status = 'deleted';
            } elseif ($this->isSimilarSongName($request->song_name)) {
                $status = 'pending';
            }

            // Tạo bài hát mới
            $song = new Song();
            $song->song_name = $request->song_name;
            $song->author_id = $request->author_id;
            $song->area_id = $request->area_id;
            $song->genre_id = $request->genre_id;
            $song->description = $request->description;
            $song->audio_path = $audioPath;  // Store the path to audio file
            $song->img_id = $request->img_id;
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

            return response()->json([
                'message' => $message,
                'song_name' => $song->song_name,
            ], $status === 'deleted' ? 202 : 201);
        } catch (\Exception $e) {
            return response()->json(['Lỗi khi upload' => $e->getMessage()], 400);
        }
    }


    private function processDash($audioPath)
    {
        // Chuyển đổi đường dẫn tuyệt đối
        $inputPath = Storage::disk('public')->path($audioPath);
        $outputDir = Storage::disk('public')->path('dash/' . pathinfo($audioPath, PATHINFO_FILENAME));

        Log::info("Input Path: $inputPath, Output Directory: $outputDir");

        // Tạo thư mục nếu nó chưa tồn tại
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Lệnh ffmpeg
        $command = "ffmpeg -i \"$inputPath\" " .
        "-map 0:a:0 -b:a:0 128k -map 0:a:0 -b:a:1 256k -map 0:a:0 -b:a:2 320k " .
        "-f dash \"$outputDir/output.mpd\"";

        // Ghi log lệnh ffmpeg
        Log::info("FFmpeg Command: $command");

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
            // Đọc nội dung file nhạc upload
            Log::info("Checking file path: $filePath");
            if (!Storage::disk('public')->exists($filePath)) {
                throw new \Exception("File không tồn tại: $filePath");
            }

            $fileContent = file_get_contents(Storage::disk('public')->path($filePath));
            if (!$fileContent) {
                throw new \Exception("Không thể đọc nội dung file $filePath");
            }

            // Tạo hash từ file nhạc upload (10KB đầu tiên)
            $uploadedHash = hash('sha256', substr($fileContent, 0, 1024 * 10));
            Log::info("Uploaded Hash: $uploadedHash");

            // Lấy danh sách file trong thư mục 'public/storage/Copyrighted_music'
            $copyrightedFiles = Storage::disk('public')->files('Copyrighted_music');
            if (empty($copyrightedFiles)) {
                Log::warning('Thư mục public/storage/Copyrighted_music không có file nào.');
            }
            // So sánh hash với các file bản quyền
            foreach ($copyrightedFiles as $file) {
                // Kiểm tra file bản quyền có tồn tại không
                if (!Storage::disk('public')->exists($file)) {
                    Log::error("File bản quyền không tồn tại: $file");
                    continue;
                }

                // Đọc nội dung file
                $absolutePath = Storage::disk('public')->path($file);
                $copyrightedContent = file_get_contents($absolutePath);
                if (!$copyrightedContent) {
                    Log::error("Không thể đọc nội dung file bản quyền: $file");
                    continue;
                }

                // Tạo hash từ file bản quyền
                $copyrightedHash = hash('sha256', substr($copyrightedContent, 0, 1024 * 10));
                Log::info("Hash của file bản quyền ($file): $copyrightedHash");

                // So sánh hash
                if ($uploadedHash === $copyrightedHash) {
                    Log::info("File nhạc trùng khớp với bản quyền: $file");
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
            ->orWhereHas('author', function ($q) use ($query) {
                $q->where('author_name', 'like', '%' . $query . '%');
            })
            ->get();

        if(Auth::check()){
                $playlists = Playlist::where('user_id', auth()->user()->id)->get();}
        else{
            $playlists = [];
        }
        return view('user/searchsong', ['songs' => $songs, 'query' => $query, 'playlists' => $playlists]);
    }
}
