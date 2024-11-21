<?php

namespace App\Http\Controllers\Song;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class SongController extends Controller
{
    public function uploadSong(Request $request)
    {
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
            'audio' => 'required|mimes:mp3,wav,ogg|max:10240', // Validate file type and size
        ]);

        // Handle audio file upload
        $audio = $request->file('audio');
        if ($audio) {
            $audioPath = $request->file('audio')->store('music', 'public'); // Store in 'public/music' folder
        } else {
            return response()->json(['message' => 'No audio file uploaded'], 400);
        }

        // Create new song entry
        $song = new Song();
        $song->song_name = $request->song_name;
        $song->author_id = $request->author_id;
        $song->area_id = $request->area_id;
        $song->genre_id = $request->genre_id;
        $song->description = $request->description;
        $song->audio_path = $audioPath;  // Store the path to audio file
        $song->img_id = $request->img_id;
        $song->status = $request->status;
        $song->lyric = $request->lyric;
        $song->save();

        return response()->json([
            'message' => 'Song uploaded successfully',
            'song_name' => $song->song_name,
        ], 201);
    }

    public function getSong($id)
    {
        $song = Song::find($id);
        if ($song) {
            // Sử dụng đường dẫn đầy đủ từ storage
            $absolutePath = url('storage/' . $song->audio_path);
            return response()->json($absolutePath, 200);
        } else {
            return response()->json(['message' => 'Song not found'], 404);
        }
    }

    public function streamAudio($id)
    {
        $song = Song::find($id);

        if (!$song || !Storage::disk('public')->exists($song->audio_path)) {
            return response()->json(['message' => 'Song not found'], 404);
        }

        $filePath = storage_path('app/public/' . $song->audio_path);
        $fileSize = filesize($filePath);
        $start = 0;
        $length = $fileSize;

        // Xử lý byte-range từ header
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            list($start, $end) = explode('-', substr($range, 6));
            $start = intval($start);
            $end = $end ? intval($end) : $fileSize - 1;
            $length = $end - $start + 1;

            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes $start-$end/$fileSize");
        } else {
            header('HTTP/1.1 200 OK');
        }

        header('Content-Type: audio/mpeg');
        header('Accept-Ranges: bytes');
        header("Content-Length: $length");

        $file = fopen($filePath, 'rb');
        fseek($file, $start);
        echo fread($file, $length);
        fclose($file);
        exit;
    }




    //Chuyển bài hát tiếp theo
    public function nextSong($id)
    {
        $song = Song::find($id);
        if ($song) {
            $nextSong = Song::where('id', '>', $id)->first();
            if ($nextSong) {
                return response()->json($nextSong, 200);
            } else {
                return response()->json(['message' => 'No next song'], 404);
            }
        } else {
            return response()->json(['message' => 'Song not found'], 404);
        }
    }

    //Chuyển bài hát trước đó
    public function prevSong($id)
    {
        $song = Song::find($id);
        if ($song) {
            $prevSong = Song::where('id', '<', $id)->orderBy('id', 'desc')->first();
            if ($prevSong) {
                return response()->json($prevSong, 200);
            } else {
                return response()->json(['message' => 'No previous song'], 404);
            }
        } else {
            return response()->json(['message' => 'Song not found'], 404);
        }
    }

    //Phát nhạc ngẫu nhiên
    public function randomSong()
    {
        $song = Song::inRandomOrder()->first();
        if ($song) {
            return response()->json($song, 200);
        } else {
            return response()->json(['message' => 'No song found'], 404);
        }
    }
}
