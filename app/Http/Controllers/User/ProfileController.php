<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile($id)
    {
        // Lấy thông tin profile của user theo ID
        $user = User::findOrFail($id);
        $author = Author::where('user_id', $id)->first();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_id' => $user->avatar_id,
            'bio' => $author->bio,
            'area_id' => $author->area_id,
        ]);
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
}
