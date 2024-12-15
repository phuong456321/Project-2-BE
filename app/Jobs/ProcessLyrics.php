<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Str;

class ProcessLyrics implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
    private function processLyrics(string $lyrics): string
    {
        $lyricPath = 'lyrics/' . Str::uuid() . '.json';

        $lyricData = [];
        $lines = explode("\n", $lyrics);
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
        return $lyricPath;
    }
}
