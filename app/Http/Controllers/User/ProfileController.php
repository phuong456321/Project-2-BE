<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile($id)
    {
        // Kiểm tra nếu người dùng hiện tại không phải là người sở hữu profile
        if (Auth::user()->id != $id) {
            return redirect('profile/' . Auth::user()->id)->with('warning', 'Unauthorized action.');
        }


        $user = User::findOrFail($id);
        $information = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'img' => $user->avatar_id,
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
        $user = User::with('author')->findOrFail($id);
        $user->update($request->only('name', 'email', 'avatar_id'));
        $user->save();
        $user->author->update($request->only('bio', 'area_id'));
        $user->author->update(['author_name' => $user->name, 'img_id' => $user->avatar_id]);
        $user->author->save();
        return response()->json($user);
    }
}
