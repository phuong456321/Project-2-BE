<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Author;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function manageSongs()
    {
        $songs = Song::with('author')->get(); // Include author relationship
        return view('admin.songs', compact('songs'));
    }

    public function manageUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
    public function manageAuthors()
    {
        $areas = Area::orderBy('name', 'asc')->get();
        return view('admin.authors', compact('areas'));
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.index');
    }

    public function deleteSong($id)
    {
        $song = Song::findOrFail($id);
        $song->delete();
        return redirect()->route('admin.index');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:published,deleted,inactive,pending',
        ]);

        $song = Song::findOrFail($id);
        $song->status = $request->input('status');
        $song->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }
}
