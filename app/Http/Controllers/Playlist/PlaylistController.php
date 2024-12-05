<?php

namespace App\Http\Controllers\Playlist;

use App\Http\Controllers\Controller;
use App\Models\InPlaylist;
use Illuminate\Http\Request;
use App\Models\playlist;
use App\Models\song;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    //Auto create Like Playlsit with token
    public function createLikePlaylist(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        $playlist = new playlist();
        $playlist->user_id = $request->user_id;
        $playlist->name = 'LikePlaylist';
        $playlist->save();

        return response()->json([
            'message' => 'Like Playlist created successfully',
            'playlist_name' => $playlist->name,
        ], 201);
    }

    //Get all playlist of user
    public function getPlaylist()
    {
        $user_id = Auth::user()->id;
        $playlists = playlist::where('user_id', $user_id)->get();
        return view('user/library', compact('playlists'));
    }

    //Create new playlist
    public function createPlaylist(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        $playlist = new playlist();
        $playlist->user_id = $request->user_id;
        $playlist->name = $request->name;
        $playlist->save();

        return response()->json([
            'message' => 'Playlist created successfully',
            'playlist_name' => $playlist->name,
        ], 201);
    }

    //Add song to playlist
    public function addSongToPlaylist(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|integer',
            'song_id' => 'required|integer',
        ]);

        $in_playlist = new InPlaylist();
        $in_playlist->playlist_id = $request->playlist_id;
        $in_playlist->song_id = $request->song_id;
        $in_playlist->save();
        return response()->json([
            'message' => 'Song added to playlist successfully',
        ], 201);
    }

    //Get all song in playlist
    public function getSongInPlaylist($playlist_id)
    {
        $in_playlist = InPlaylist::where('playlist_id', $playlist_id)->get();
        $songs = [];
        $totalSeconds = 0;
        $playlist = Playlist::where('user_id', Auth::user()->id)->get();
        foreach ($in_playlist as $item) {
            $song = song::find($item->song_id);

            // Truy xuất tên của tác giả
            $author_name = Author::where('id', $song->author_id)->value('author_name');
            $song->author_name = $author_name;

            //audio_path trả về url của file audio
            if ($song) {
                $song->audio_path = url('storage/' . $song->audio_path);

                 // Tính tổng thời gian bài hát
             $parts = explode(':', $song->duration); // duration dạng 'mm:ss'
             $minutes = intval($parts[0]);
            $seconds = intval($parts[1]);
            $totalSeconds += $minutes * 60 + $seconds;
                
            }
            array_push($songs, $song);
        }
        // Chuyển đổi tổng giây thành định dạng mm:ss hoặc hh:mm:ss
    $hours = floor($totalSeconds / 3600);
    $minutes = floor(($totalSeconds % 3600) / 60);
    $seconds = $totalSeconds % 60;

    $totalDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // hh:mm:ss
        return view('user/playist', ['songs' => $songs, 'playlists' => $playlist, 'playlist_id' => $playlist_id, 'totalDuration' => $totalDuration]);
    }


    //Remove song from playlist
    public function removeSongFromPlaylist(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|integer',
            'song_id' => 'required|integer',
        ]);

        $in_playlist = InPlaylist::where('playlist_id', $request->playlist_id)->where('song_id', $request->song_id)->first();
        if ($in_playlist) {
            $in_playlist->delete();
            return response()->json([
                'message' => 'Song removed from playlist successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Song not found in playlist',
            ], 404);
        }
    }

    //Delete playlist
    public function deletePlaylist(Request $request)
    {
        $playlist_id = $request->playlist_id;
        $playlist = playlist::find($playlist_id);
        if ($playlist) {
            // Xóa các bản ghi liên quan trong bảng in_playlists
            DB::table('in_playlists')->where('playlist_id', $playlist_id)->delete();

            // Sau đó mới xóa playlist
            $playlist->delete();

            return response()->json([
                'message' => 'Playlist deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Playlist not found',
            ], 404);
        }
    }
}
