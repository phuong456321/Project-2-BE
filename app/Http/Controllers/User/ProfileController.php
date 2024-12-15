<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Image;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class ProfileController extends Controller
{
    public function showProfile($id)
    {
        // Kiểm tra nếu người dùng hiện tại không phải là người sở hữu profile
        if (Auth::user()->id != $id) {
            return redirect('profile/' . Auth::user()->id)->with('warning', 'Unauthorized action.');
        }

        $playlists = Playlist::where('user_id', Auth::user()->id)->get();
        $user = User::with('author')->where('id', Auth::user()->id)->first();
        $notifications = Auth::user()->notifications;
        $song = Song::where('author_id', $user->author_id)->get();
        return view('user/Profileuser', ['user' => $user, 'playlists' => $playlists, 'songs' => $song, 'notifications' => $notifications]);
    }

    public function editProfile()
    {
        $user = User::with('author')->where('id', Auth::user()->id)->first();
        return view('setting/editprofile', ['user' => $user]);
    }
    public function updateProfile(Request $request)
{
    try {
        // Validate request
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'sometimes|string|max:255'
        ]);

        $user = User::with('author')->findOrFail(Auth::id());

        // Xử lý avatar (nếu có)
        $avatarId = $user->avatar_id; // Giữ avatar cũ mặc định
        if ($request->hasFile('avatar')) {
            $avatar = file_get_contents($request->file('avatar'));
            $imageName = Str::uuid() . '.webp';
            Storage::disk('public')->put('images/' . $imageName, $avatar);

            $newImage = Image::create([
                'img_name' => $imageName,
                'img_path' => 'images/' . $imageName,
                'category' => 'avatar',
            ]);

            Storage::disk('public')->delete($user->avatar->img_path);

            $avatarId = $newImage->id; // Cập nhật avatar mới
        }

        // Cập nhật thông tin người dùng
        $user->update(array_merge(
            $request->only('name', 'email'),
            ['avatar_id' => $avatarId]
        ));

        // Cập nhật thông tin của author (nếu tồn tại)
        if ($user->author) {
            $user->author->update(array_merge(
                $request->only('bio'),
                [
                    'author_name' => $user->name,
                    'img_id' => $avatarId
                ]
            ));
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function uploadedsong()
    {
        $songs = Song::where('author_id', Auth::user()->author_id)->with('genre', 'area')->get();
        return view('setting/uploadedsong', ['songs' => $songs]);
    }
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
