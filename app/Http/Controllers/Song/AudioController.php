<?php

namespace App\Http\Controllers\Song;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AudioController extends Controller
{
    public function streamAudio(Request $request, $filePath )
{
    $filePath = public_path('storage/music/' . $filePath);
    if (!file_exists($filePath)) {
        abort(404, 'File not found.' . $filePath);
    }

    $fileSize = filesize($filePath);
    $start = 0;
    $end = $fileSize - 1;

    // Kiểm tra Range từ tiêu đề của Request
    if ($request->headers->has('Range')) {
        $range = $request->header('Range');
        preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
        $start = intval($matches[1]);
        if (!empty($matches[2])) {
            $end = intval($matches[2]);
        }
    }

    $length = $end - $start + 1;

    $headers = [
        'Content-Type' => 'audio/mpeg',
        'Content-Length' => $length,
        'Content-Range' => "bytes $start-$end/$fileSize",
        'Accept-Ranges' => 'bytes',
    ];

    $response = new StreamedResponse(function () use ($filePath, $start, $length) {
        $handle = fopen($filePath, 'rb');
        fseek($handle, $start);
        echo fread($handle, $length);
        fclose($handle);
    }, 206, $headers);

        return $response;
    }

    public function index(){
        $songs = Song::inRandomOrder()->where('status', '=', 'published')->take(7)->get();
        if(Auth::check()){
            $user = Auth::user();
            $playlists = $user->playlists()->with(['songs' => function ($query) {
                $query->orderBy('in_playlists.created_at')->limit(1);
            }])->get();
        } else {
            $playlists = [];
        }
        return view('user.home', compact('playlists', 'songs'));
    }

      // Phương thức xử lý yêu cầu tải file manifest .mpd
    public function playAudio($file)
    {
        // Đường dẫn đến file manifest .mpd
        $filePath = storage_path("app/public/dash/{$file}");

        // Kiểm tra nếu file tồn tại
        if (!Storage::exists("public/dash/{$file}")) {
            abort(404, "File manifest .mpd không tìm thấy");
        }

        // Trả về file .mpd (Manifest) với Content-Type là application/dash+xml
        return response()->file($filePath, [
            'Content-Type' => 'application/dash+xml',
        ]);
    }

    // Phương thức xử lý các segment .m4s
    public function streamSegment($file)
    {
        // Đường dẫn đến các segment .m4s
        $filePath = storage_path("app/public/dash/{$file}");

        // Kiểm tra nếu file tồn tại
        if (!Storage::exists("public/dash/{$file}")) {
            abort(404, "Segment .m4s không tìm thấy");
        }

        // Trả về segment .m4s với Content-Type là audio/mp4
        return response()->file($filePath, [
            'Content-Type' => 'video/mp4', // Hoặc 'video/mp4' tùy thuộc vào loại segment
        ]);
    }
}
