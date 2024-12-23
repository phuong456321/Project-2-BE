<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
class CheckSimilarSongName implements ShouldQueue
{
    use Queueable;

     protected $songName;

    public function __construct($songName)
    {
        $this->songName = $songName;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Đọc tên bài hát bản quyền từ tệp JSON
        $songNames = Storage::disk('public')->get('copyrighted_song_names.json');
        if ($songNames) {
            $ArraySongNames = json_decode($songNames, true);
            $ArraySongNames[] = $this->songName;

            // Lưu danh sách tên bài hát vào tệp JSON
            Storage::disk('public')->put('copyrighted_song_names.json', json_encode($ArraySongNames));
        }
    }
}
