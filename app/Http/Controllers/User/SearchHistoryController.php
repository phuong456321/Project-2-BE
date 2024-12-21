<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SearchHistory;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    public static function store(Request $request)
    {
        // Kiểm tra và validate dữ liệu
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'content' => 'required|string|max:255',
            'clicked_song_id' => 'nullable|exists:songs,song_id', // Nullable vì có thể không có clicked_song_id
        ]);

        // Kiểm tra nếu lịch sử tìm kiếm đã tồn tại (tránh trùng)
        $existingHistory = SearchHistory::where('user_id', $request->user_id)
            ->where('content', $request->content)
            ->first();

        // Nếu không tìm thấy lịch sử tìm kiếm này, lưu nó
        if (!$existingHistory) {
            SearchHistory::create([
                'user_id' => $request->user_id,
                'content' => $request->content,
                'clicked_song_id' => $request->clicked_song_id, // Có thể là null nếu không nhấn bài hát
            ]);
        }

        return response()->json(['message' => 'Lịch sử tìm kiếm đã được lưu thành công!']);
    }
}
