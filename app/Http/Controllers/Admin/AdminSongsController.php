<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Http\Request;

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
            $query->where('genre_id', $request->genre);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get filtered songs
        $songs = $query->with('author', 'genre')->paginate(10);

        // Fetch all genres for filter dropdown
        $genres = Genre::all();

        // Fetch all authors for filter dropdown
        $authors = Author::all();

        return view('admin.songs', compact('songs', 'genres', 'authors'));
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
            'song_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
        ]);

        // Cập nhật thông tin bài hát
        $song->update($request->only('song_name', 'description', 'genre_id'));

        return redirect()->route('admin.songs')->with('success', 'Song updated successfully.');
    }

}
