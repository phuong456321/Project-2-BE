<?php

namespace App\Http\Controllers\Song;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Song;

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
                'audio' => 'required|mimes:mp3,wav,ogg|max:10240', // Validate file type and size
            ]);

            // Handle audio file upload
            $audio = $request->file('audio');
            if ($audio) {
                $audioPath = $audio->store('music', 'public'); // Store in 'public/music' folder
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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getSong($id)
    {
        $song = Song::find($id);
        if ($song) {
            return response()->json($song, 200);
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
        return view('user/searchsong', ['songs' => $songs, 'query' => $query]);
    }
}
