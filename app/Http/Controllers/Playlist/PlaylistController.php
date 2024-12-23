<?php

namespace App\Http\Controllers\Playlist;

use App\Http\Controllers\Controller;
use App\Models\DetailsPlayed;
use App\Models\InPlaylist;
use App\Models\RecentlyPlayed;
use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{

    //Get all playlist of user
    public function getPlaylist()
    {
        $user_id = Auth::user()->id;
        $playlists = playlist::where('user_id', $user_id)->with('songs')->get();
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

    //Get all song in playlist
    public function getSongInPlaylist($playlist_id)
    {
        $in_playlist = InPlaylist::where('playlist_id', $playlist_id)->get();
        $songs = [];
        $totalSeconds = 0;
        $playlist = Playlist::where('user_id', Auth::user()->id)->get();
        foreach ($in_playlist as $item) {
            $song = Song::with('author')->find($item->song_id);
            // Truy xuất tên của tác giả
            $author_name = Author::where('id', $song->author_id)->value('author_name');
            $song->author_name = $author_name;

            //audio_path trả về url của file audio
            if ($song) {
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


    //Thích nhạc và thêm vào playlist
    public function likeSong(Request $request)
    {
        $request->validate([
            'song_id' => 'required|integer',
        ]);

        $user_id = Auth::user()->id;
        // Kiểm tra xem bài hát đã được like chưa
        $alreadyLiked = $this->checkIfLiked($request); // Gọi hàm checkIfLiked

        if ($alreadyLiked) {
            // Nếu bài hát đã được like, bỏ thích (set like = like -1)
            $song = Song::find($request->song_id);
            $song->likes -= 1;
            $song->save();

            // Lấy id của playlist "Liked music"
            $playlist_id = Playlist::where('user_id', $user_id)->where('name', 'Liked music')->value('id');

            // Loại bỏ bài hát khỏi playlist
            InPlaylist::where('playlist_id', $playlist_id)
                ->where('song_id', $request->song_id)
                ->delete();

            return response()->json([
                'message' => 'Song removed from liked music',
            ], 201);
        } else {
            // Nếu bài hát chưa được like, thực hiện thao tác thích bài hát và thêm vào playlist
            $song = Song::find($request->song_id);
            $song->likes += 1;
            $song->save();

            // Lấy id của playlist "Liked music"
            $playlist_id = Playlist::where('user_id', $user_id)->where('name', 'Liked music')->value('id');

            // Thêm bài hát vào playlist
            $in_playlist = new InPlaylist();
            $in_playlist->playlist_id = $playlist_id;
            $in_playlist->song_id = $request->song_id;
            $in_playlist->save();

            return response()->json([
                'message' => 'Song added to liked music',
            ], 201);
        }
    }

    public function checkIfLiked(Request $request)
    {
        $request->validate([
            'song_id' => 'required|integer',
        ]);

        Song::where('id', $request->song_id)->update([
            'play_count' => DB::raw('play_count + 1')
        ]);

        $user_id = Auth::user()->id;
        if (!$user_id) {
            return false;
        }
        // Tìm hoặc tạo recently_played cho user
        $recentlyPlayed = RecentlyPlayed::firstOrCreate([
            'user_id' => $user_id
        ]);

        // Kiểm tra xem bài hát đã được lưu trong 5 phút qua chưa
        $exists = DetailsPlayed::where('recently_id', $recentlyPlayed->id)
            ->where('song_id', $request->song_id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists(); // Sử dụng exists() để kiểm tra nhanh

        if (!$exists) {
            // Nếu không tìm thấy bản ghi trong vòng 5 phút, thì mới lưu
            $details = DetailsPlayed::create([
                'recently_id' => $recentlyPlayed->id,
                'song_id' => $request->song_id
            ]);
        }
        // Lấy id của playlist "Liked music"
        $playlist_id = Playlist::where('user_id', $user_id)->where('name', 'Liked music')->value('id');

        if (!$playlist_id) {
            return false; // Nếu không có playlist "Liked music", trả về false
        }

        // Kiểm tra xem bài hát đã có trong playlist chưa
        $liked = InPlaylist::where('playlist_id', $playlist_id)
            ->where('song_id', $request->song_id)
            ->exists();

        return $liked; // Trả về true nếu bài hát đã được like, ngược lại false
    }
    //Add song to playlist
    public function addSongToPlaylist(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|integer',
            'song_id' => 'required|integer',
        ]);
        InPlaylist::where('playlist_id', $request->playlist_id)->where('song_id', $request->song_id)->delete();
        $in_playlist = new InPlaylist();
        $in_playlist->playlist_id = $request->playlist_id;
        $in_playlist->song_id = $request->song_id;
        $in_playlist->save();
        return response()->json([
            'message' => 'Song added to playlist successfully',
        ], 201);
    }

    public function getPlaylistSongs($playlist_id)
{
    $songs = Song::whereIn('id', function ($query) use ($playlist_id) {
        $query->select('song_id')
              ->from('in_playlists')
              ->where('playlist_id', $playlist_id);
    })
    ->with('author') // Nạp quan hệ với author
    ->get();

    return response()->json($songs, 200);
}

}
