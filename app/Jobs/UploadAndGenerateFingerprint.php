<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class UploadAndGenerateFingerprint implements ShouldQueue
{
    use Queueable;

    public $audioPath;
    public $absolutePath;
    public $ClientOriginalName;
    /**
     * Create a new job instance.
     */
    public function __construct($audioPath, $absolutePath, $ClientOriginalName)
    {
        $this->audioPath = $audioPath;
        $this->absolutePath = $absolutePath;
        $this->ClientOriginalName = $ClientOriginalName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $chunkSize = 200 * 1024; // 200KB (thay đổi nếu cần)
        $fileSize = filesize($this->absolutePath);

        // **Trích xuất 200KB đầu**
        $handle = fopen($this->absolutePath, 'rb');
        $dataFirst = fread($handle, $chunkSize); // Đọc từ đầu file
        fclose($handle);

        $firstChunkFileName = pathinfo($this->ClientOriginalName, PATHINFO_FILENAME) . '_first200KB.mp3';
        $firstChunkFilePath = storage_path("app/public/temp/$firstChunkFileName");
        file_put_contents($firstChunkFilePath, $dataFirst);

        // Tạo fingerprint cho đoạn đầu
        $outputFirst = [];
        exec("fpcalc -json \"$firstChunkFilePath\"", $outputFirst);
        $resultFirst = json_decode(implode('', $outputFirst), true);

        // Kiểm tra nếu không tạo được fingerprint cho đoạn đầu
        if (empty($resultFirst)) {
            Storage::disk('public')->delete($this->audioPath);
            Storage::disk('public')->delete($firstChunkFilePath);
            \Log::error("Could not generate fingerprint for the first 200KB of {$this->ClientOriginalName}");
            return;
        }

        // **Trích xuất 200KB cuối**
        $startByte = max(0, $fileSize - $chunkSize);

        $handle = fopen($this->absolutePath, 'rb');
        fseek($handle, $startByte); // Di chuyển con trỏ tới 200KB cuối
        $dataLast = fread($handle, $chunkSize);
        fclose($handle);

        $lastChunkFileName = pathinfo($this->ClientOriginalName, PATHINFO_FILENAME) . '_last200KB.mp3';
        $lastChunkFilePath = storage_path("app/public/temp/$lastChunkFileName");
        file_put_contents($lastChunkFilePath, $dataLast);

        // Tạo fingerprint cho đoạn cuối
        $outputLast = [];
        exec("fpcalc -json \"$lastChunkFilePath\"", $outputLast);
        $resultLast = json_decode(implode('', $outputLast), true);

        // Kiểm tra nếu không tạo được fingerprint cho đoạn cuối
        if (empty($resultLast)) {
            Storage::disk('public')->delete($this->audioPath);
            Storage::disk('public')->delete($firstChunkFilePath);
            Storage::disk('public')->delete($lastChunkFilePath);
            \Log::error("Could not generate fingerprint for the last 200KB of {$this->ClientOriginalName}");
            return;
        }

        // Lưu thông tin fingerprint vào JSON
        $jsonFileName = pathinfo($this->ClientOriginalName, PATHINFO_FILENAME) . '.json';
        $jsonPath = "Copyrighted_music/$jsonFileName";

        $dataToSave = [
            'first_200KB_fingerprint' => $resultFirst,
            'last_200KB_fingerprint' => $resultLast,
        ];

        Storage::disk('public')->put($jsonPath, json_encode($dataToSave, JSON_PRETTY_PRINT));

        // Xóa file tạm
        Storage::disk('public')->delete($this->audioPath);
        Storage::disk('public')->delete("temp/$firstChunkFileName");
        Storage::disk('public')->delete("temp/$lastChunkFileName");
    }
}
