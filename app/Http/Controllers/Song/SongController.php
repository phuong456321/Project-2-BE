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
                'author' => 'required|integer',
                'area' => 'required|integer',
                'genre' => 'required|integer',
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
                'author',
                'area',
                'genre',
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
        $song = Song::with('author')->find($id);
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

    public function removeSelected(Request $request)
    {
        // Lấy mảng song_ids từ request
        $songIds = $request->input('song_ids');

        try {
            // Xóa các bài hát có trong songIds
            Song::whereIn('id', $songIds)->delete();
            flash()->success('Songs removed successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về phản hồi thất bại
            flash()->error('Error removing songs: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
