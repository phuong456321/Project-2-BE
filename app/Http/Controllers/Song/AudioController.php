<?php

namespace App\Http\Controllers\Song;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Playlist;
use Auth;
use Illuminate\Http\Request;
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
        $authors = Author::inRandomOrder()->take(7)->get();
        if(Auth::check()){
            $playlists = Playlist::where('user_id', auth()->user()->id)->get();}
        else{
            $playlists = [];
        }
        return view('user.home', compact('authors', 'playlists'));
    }
}
