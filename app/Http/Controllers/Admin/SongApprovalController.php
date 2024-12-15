<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Song;
use App\Models\User;
use App\Notifications\SongProcessedNotification;
use Illuminate\Http\Request;

class SongApprovalController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài hát chưa duyệt cùng với tác giả và số bài bị từ chối của tác giả
        $songs = Song::with([
            'author' => function ($query) {
                $query->withCount([
                    'songs as restricted_tracks_count' => function ($query) {
                        $query->whereIn('status', ['inactive', 'deleted']);
                    }
                ]);
            }
        ])->where('status', 'pending')->get();
        // Duyệt qua từng bài hát và lấy các bài hát trùng tên
        foreach ($songs as $song) {
            $song->related_songs = Song::where('song_name', 'LIKE', '%' . $song->song_name . '%')->where('id', '!=', $song->id)->where('status', '=', 'published')->get();
        }

        return view('admin.song-approval', compact('songs'));
    }

    public function approve($id)
    {
        $song = Song::findOrFail($id);
        $song->status = 'published';
        $song->save();

        $user = User::where('author_id', $song->author_id)->first();
        if ($user) {
            $user->notify(new SongProcessedNotification(
                $song->song_name,
                'published',
                'Bài hát của bạn đã được duyệt thành công!'
            ));
        }
        return redirect()->route('admin.songApproval')->with('success', 'Song approved successfully!');
    }

    public function reject($id)
    {
        $song = Song::findOrFail($id);
        $song->status = 'deleted';
        $song->save();

        $user = User::where('author_id', $song->author_id)->first();
        if ($user) {
            $user->notify(new SongProcessedNotification(
                $song->song_name,
                'rejected',
                'Bài hát của bạn đã bị từ chối!'
            ));
        }
        return redirect()->route('admin.songApproval')->with('success', 'Song rejected and deleted!');
    }
}
