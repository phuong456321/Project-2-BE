<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessLyrics;
use App\Jobs\ProcessUploadedSong;
use App\Jobs\CheckSimilarSongName;
use App\Jobs\UploadAndGenerateFingerprint;
use App\Models\Area;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSongsController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::query();

        // Apply filters
        if ($request->filled('song_name')) {
            $query->where('song_name', 'like', '%' . $request->song_name . '%');
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre)->get();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get filtered songs
        // Lấy tất cả các bài hát sau khi áp dụng bộ lọc
        $songs = $query->with('author', 'genre')->get();

        // Fetch all genres for filter dropdown
        $genres = Genre::all();

        // Fetch all authors for filter dropdown
        $authors = Author::all();

        // Fetch all areas for filter dropdown
        $areas = Area::all();

        return view('admin.songs', compact('songs', 'genres', 'authors', 'areas'));
    }
    // Cập nhật trạng thái bài hát
    public function updateStatus(Request $request, $id)
    {
        $song = Song::findOrFail($id);
        $song->status = $request->status;
        $song->save();

        return redirect()->route('admin.songs')->with('success', 'Song status updated successfully.');
    }

    // Hiển thị popup chỉnh sửa bài hát
    public function edit($id)
    {
        $song = Song::findOrFail($id);
        $genres = Genre::all();
        $authors = Author::all();

        return view('admin.edit-song', compact('song', 'genres', 'authors'));
    }

    // Cập nhật bài hát từ popup
    public function update(Request $request, $id)
    {
        $song = Song::findOrFail($id);

        // Validate input
        $request->validate([
            'song_name' => 'sometimes|string|max:255',
            'genre_id' => 'sometimes|exists:genres,id',
            'author_id' => 'sometimes|exists:authors,id',
            'area_id' => 'sometimes|exists:areas,id',
        ]);

        // Cập nhật thông tin bài hát
        $song->update($request->only('song_name', 'genre_id', 'author_id', 'area_id'));

        return redirect()->route('admin.songs')->with('success', 'Song updated successfully.');
    }

    public function create(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'song_name' => 'required|string',
                'author' => 'required|integer',
                'area' => 'required|integer',
                'genre' => 'required|integer',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|string',
                'lyric' => 'nullable|string',
                'audio' => 'required|mimes:mp3,wav,ogg|max:20240', // Max 20MB
            ]);
            // Kiểm tra lỗi validate
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

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

            //ProcessUploadedSong::dispatch($songData, $imagePath, $audioPath);
            $absolutePath = storage_path("app/public/$audioPath");
            //UploadAndGenerateFingerprint::dispatch($audioPath, $absolutePath, $request->file('audio')->getClientOriginalName());
            //CheckSimilarSongName::dispatch($request->song_name);
            // Dispatch các job qua queue theo chuỗi
            ProcessUploadedSong::dispatch($songData, $imagePath, $audioPath)
            ->chain([
                new UploadAndGenerateFingerprint($audioPath, $absolutePath, $request->file('audio')->getClientOriginalName()),
                new CheckSimilarSongName($request->song_name),
            ]);
            return redirect()->back()->with('success', 'Bài hát đang được xử lý');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    public function asyncLyrics(Request $request)
    {
        $request->validate([
            'lyrics' => 'required|string',
            'song_id' => 'required|integer',
        ]);

        // Lấy lời bài hát từ form
        $lyrics = $request->input('lyrics');

        // Lấy ID bài hát
        $songId = $request->input('song_id');

        // Gọi job xử lý lyrics
        dispatch(new ProcessLyrics($lyrics, $songId));

        return redirect()->route('admin.songs')->with('success', 'Lyrics processed successfully.');
    }
}
