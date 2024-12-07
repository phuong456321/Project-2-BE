<?php

namespace App\Http\Controllers\Song;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecommendSongs extends Controller
{
    public function index(Request $request)
    {
        $songs = Song::inRandomOrder()->where('status', '=', 'published')->take(21)->get();
        if (Auth::check()) {
            $user = Auth::user();
            $playlists = $user->playlists()->with([
                'songs' => function ($query) {
                    $query->orderBy('in_playlists.created_at')->limit(1);
                }
            ])->get();
        } else {
            $playlists = [];
            return view('user.home')->with(['songs' => $songs, 'playlists' => $playlists]);
        }

        $userId = $request->user()->id;
        // Dữ liệu từ CBF
        $recommendedSongsByContent = $this->getContentBasedRecommendations($userId);

        // Dữ liệu từ CF
        $recommendedSongsByBehavior = $this->getCollaborativeRecommendations($userId);
        // Gộp kết quả
        $recommendedSongs = $this->combineRecommendations($recommendedSongsByContent, $recommendedSongsByBehavior);

        return view('user.home')->with(['songs' => $recommendedSongs, 'playlists' => $playlists]);
    }

    protected function getContentBasedRecommendations($userId)
    {
        // Lấy thuộc tính các bài hát mà người dùng đã nghe
        $songAttributes = DB::table('details_played')
            ->join('recently_playeds', 'details_played.recently_id', '=', 'recently_playeds.id')
            ->join('songs', 'details_played.song_id', '=', 'songs.id')
            ->where('recently_playeds.user_id', $userId)
            ->where('songs.status', '=', 'published')
            ->select('songs.genre_id', 'songs.author_id', 'songs.area_id')
            ->distinct();

        // Lấy thêm thuộc tính từ lịch sử tìm kiếm
        $searchQuery = DB::table('search_history')
            ->join('songs', 'search_history.clicked_song_id', '=', 'songs.id')
            ->where('search_history.user_id', $userId)
            ->where('songs.status', '=', 'published')
            ->select('songs.genre_id', 'songs.author_id', 'songs.area_id')
            ->distinct()
            ->union($songAttributes);

        // Lấy attributes từ query
        $searchAttributes = $searchQuery->get();

        if ($searchAttributes->isEmpty()) {
            return collect();
        }

        // Tạo subquery để tính điểm matching
        $matchingScoreQuery = DB::table('songs as s')
            ->select(
                's.id',
                DB::raw('SUM(
                    CASE 
                        WHEN s.genre_id = a.genre_id THEN 3  /* Genre match weight */
                        ELSE 0 
                    END +
                    CASE 
                        WHEN s.author_id = a.author_id THEN 2  /* Author match weight */
                        ELSE 0 
                    END +
                    CASE 
                        WHEN s.area_id = a.area_id THEN 1  /* Area match weight */
                        ELSE 0 
                    END
                ) as matching_score')
            )
            ->crossJoin(DB::raw('(' . $searchQuery->toSql() . ') as a'))
            ->mergeBindings($searchQuery)
            ->groupBy('s.id');

        // Truy vấn bài hát gợi ý dựa trên matching score
        $recommendedSongs = DB::table('songs')
            ->joinSub($matchingScoreQuery, 'matches', function($join) {
                $join->on('songs.id', '=', 'matches.id');
            })
            ->where('songs.status', '=', 'published')
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('details_played')
                    ->join('recently_playeds', 'details_played.recently_id', '=', 'recently_playeds.id')
                    ->where('recently_playeds.user_id', $userId)
                    ->whereColumn('details_played.song_id', 'songs.id');
            })
            ->orderBy('matches.matching_score', 'desc')
            ->select('songs.*', 'matches.matching_score')
            ->take(10)
            ->get();

        return $recommendedSongs;
    }


    protected function getCollaborativeRecommendations($userId)
    {
        // Lấy bài hát đã nghe gần đây của người dùng
        $userRecentlyPlayed = DB::table('details_played')
            ->join('recently_playeds', 'details_played.recently_id', '=', 'recently_playeds.id')
            ->where('recently_playeds.user_id', $userId)
            ->pluck('details_played.song_id');

        if ($userRecentlyPlayed->isEmpty()) {
            return collect(); // Trả về danh sách rỗng nếu không có bài hát
        }

        // Tìm người dùng khác có hành vi tương tự
        $similarUsers = DB::table('details_played')
            ->join('recently_playeds', 'details_played.recently_id', '=', 'recently_playeds.id')
            ->whereIn('details_played.song_id', $userRecentlyPlayed)
            ->where('recently_playeds.user_id', '!=', $userId)
            ->groupBy('recently_playeds.user_id')
            ->pluck('recently_playeds.user_id');

        if ($similarUsers->isEmpty()) {
            return collect(); // Trả về danh sách rỗng nếu không tìm thấy người dùng tương tự
        }

        // Lấy bài hát được nghe nhiều bởi người dùng tương tự
        $recommendedSongsByBehavior = DB::table('details_played')
            ->join('recently_playeds', 'details_played.recently_id', '=', 'recently_playeds.id')
            ->join('songs', 'details_played.song_id', '=', 'songs.id') // Join để kiểm tra status
            ->whereIn('recently_playeds.user_id', $similarUsers)
            ->where('songs.status', '=', 'published') // Chỉ lấy bài hát đã xuất bản
            ->groupBy('details_played.song_id')
            ->orderByRaw('COUNT(details_played.song_id) DESC')
            ->limit(10)
            ->pluck('details_played.song_id');

        return $recommendedSongsByBehavior;
    }


    protected function combineRecommendations($recommendedSongsByContent, $recommendedSongsByBehavior)
    {
        // Điều chỉnh trọng số cho CBF và CF
        $alpha = 0.6; // Tăng trọng số cho Content-Based vì có thêm dữ liệu tìm kiếm
        $beta = 1 - $alpha;

        // Chuyển đổi kết quả CBF và CF thành mảng điểm
        $contentScores = [];
        foreach ($recommendedSongsByContent as $song) {
            $contentScores[$song->id] = ($contentScores[$song->id] ?? 0) + 1;
        }

        $behaviorScores = [];
        foreach ($recommendedSongsByBehavior as $songId) {
            $behaviorScores[$songId] = ($behaviorScores[$songId] ?? 0) + 1;
        }

        // Gộp điểm
        $finalScores = [];
        $songIds = array_unique(array_merge(array_keys($contentScores), array_keys($behaviorScores)));
        foreach ($songIds as $songId) {
            $contentScore = $contentScores[$songId] ?? 0;
            $behaviorScore = $behaviorScores[$songId] ?? 0;
            $finalScores[$songId] = $alpha * $contentScore + $beta * $behaviorScore;
        }

        // Sắp xếp và trả về kết quả
        arsort($finalScores);
        $recommendedSongIds = array_keys(array_slice($finalScores, 0, 10, true));

        // Lấy thông tin bài hát
        $recommendedSongs = Song::whereIn('id', $recommendedSongIds)
            ->with(['author', 'genre']) // Eager load relationships
            ->get();

        return $recommendedSongs;
    }
}
