<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;

class SongApprovalController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài hát chưa duyệt
        $songs = Song::where('status', 'pending')->get();

        return view('admin.song-approval', compact('songs'));
    }

    public function approve($id)
    {
        $song = Song::findOrFail($id);
        $song->is_approved = true;
        $song->save();

        return redirect()->route('admin.songApproval')->with('success', 'Song approved successfully!');
    }

    public function reject($id)
    {
        $song = Song::findOrFail($id);
        $song->delete();

        return redirect()->route('admin.songApproval')->with('success', 'Song rejected and deleted!');
    }
}
