<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Lấy trang hiện tại và số lượng người dùng cần tải
        $page = $request->input('page', 1); // Mặc định là trang 1
        $perPage = 10; // Số lượng người dùng mỗi lần tải

        $query = User::query();

        // Lọc theo từ khóa tìm kiếm nếu có
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái người dùng
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Lấy tất cả người dùng và đếm số bài nhạc bị restricted
        $users = $query->withCount([
            'author as restricted_tracks_count' => function ($query) {
                $query->whereHas('songs', function ($q) {
                    $q->where('status', 'inactive');
                });
            }
        ])->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([], 200); // Trả về một mảng rỗng nếu không còn người dùng
        }
        return response()->json($users);
    }

    public function banUser(Request $request, User $user)
    {
        try {
            // Cập nhật trạng thái thành inactive
            $user->status = 'inactive';
            $user->save();

            return response()->json(['message' => 'User has been banned successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to ban user.'], 500);
        }
    }
    public function unbanUser(Request $request, User $user)
    {
        try {
            // Cập nhật trạng thái thành active
            $user->status = 'active';
            $user->save();

            return response()->json(['message' => 'User has been unbanned successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to unban user.'], 500);
        }
    }

}
