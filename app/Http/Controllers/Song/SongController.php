<?php

namespace App\Http\Controllers\Song;

use App\Jobs\ProcessUploadedSong;
use App\Models\Genre;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\SearchHistoryController;
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
use Illuminate\Support\Facades\Http;

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


            $imagePath = $request->file('image')->store('temp', 'public');
            $audioPath = $request->file('audio')->store('temp', 'public');

            // Lấy các dữ liệu khác từ request
            $songData = $request->only([
                'song_name',
                'author_id',
                'area_id',
                'genre_id',
                'description',
                'status',
                'lyric',
            ]);

            ProcessUploadedSong::dispatch($songData, $imagePath, $audioPath);

            flash()->success('Bài hát của bạn đang được xử lý. Bạn sẽ nhận được thông báo sau.');
            return redirect()->route('profile', Auth::user()->id);
        } catch (\Exception $e) {
            return response()->json(['Lỗi khi upload' => $e->getMessage()], 400);
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
            $playlists = Playlist::where('user_id', Auth::user()->id)->get();
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
