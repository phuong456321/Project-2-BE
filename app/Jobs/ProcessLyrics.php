<?php

namespace App\Jobs;

use App\Models\Song;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Str;

class ProcessLyrics implements ShouldQueue
{
    use Queueable;

    protected $lyrics;
    protected $songId;
    /**
     * Create a new job instance.
     */
    public function __construct(string $lyrics, int $songId)
    {
        $this->lyrics = $lyrics;
        $this->songId = $songId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $lyricPath = 'lyrics/' . Str::uuid() . '.json';

        $lyricData = [];
        $lines = explode("\n", $this->lyrics);
        foreach ($lines as $index => $line) {
            preg_match('/\[(\d{2}:\d{2}.\d{1,2})\](.*)/', $line, $matches);
            if (count($matches) === 3) {
                $lyricData[] = [
                    'time' => $matches[1], // Thời gian dạng mm:ss.SS
                    'text' => trim($matches[2]), // Lyric nội dung
                ];
            }
        }

        Storage::disk('public')->put($lyricPath, json_encode($lyricData));
        // Cập nhật đường dẫn lyrics vào bảng songs
        $song = Song::find($this->songId);
        if ($song) {
            $song->update(['lyric_path' => $lyricPath]);
        }
    }
}
