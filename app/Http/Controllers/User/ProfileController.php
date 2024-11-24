<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile($id)
    {
        // Lấy thông tin profile của user theo ID
        $user = User::findOrFail($id);
        $author = Author::where('user_id', $id)->first();

        
        $bio = $author ? $author->bio : null;
        $area_id = $author ? $author->area_id : null;
        $information = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'img' => $user->avatar_id,
            'bio' => $bio,
            'area_id' => $area_id,
        ];
        return view('user/profile', compact('information'));
    }

    public function editProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users',
            'avatar_id' => 'sometimes|required|integer',
            'bio' => 'sometimes|required|string|max:255',
            'area_id' => 'sometimes|required|integer',
        ]);
        // Cập nhật thông tin profile của user
        $user = User::findOrFail($id);
        $user->update($request->only('name', 'email', 'avatar_id'));
        $user->save();
        $author = Author::where('user_id', $id)->first();
        $author->update($request->only('bio', 'area_id'));
        $author->update(['author_name' => $user->name, 'img_id' => $user->avatar_id]);
        $author->save();
        return response()->json($user);
    }

    public function showImage($id)
{
    // Truy vấn hình ảnh từ cơ sở dữ liệu
    $image = Image::where('img_id', $id)->first();

    // Kiểm tra nếu hình ảnh tồn tại
    if ($image) {
        // Trả về hình ảnh với header Content-Type đúng
        return response($image->img_path)
            ->header('Content-Type', 'image/jpeg');  // Hoặc loại MIME tương ứng với hình ảnh (PNG, JPG, GIF, ...)
    }

    // Nếu không tìm thấy hình ảnh
        return abort(404, 'Image not found');
    }
}
