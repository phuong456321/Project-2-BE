<?php

namespace App\Jobs;

use App\Models\Image;
use App\Models\Song;
use App\Notifications\SongProcessedNotification;
use getID3;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Str;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;



class ProcessUploadedSong implements ShouldQueue
{
    use Queueable;


    public $data; // Dữ liệu request từ người dùng
    public $imagePath; // Đường dẫn ảnh
    public $audioPath; // Đường dẫn audio

    /**
     * Create a new job instance.
     */
    public function __construct($data, $imagePath, $audioPath)
    {
        $this->data = $data;
        $this->imagePath = $imagePath;
        $this->audioPath = $audioPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Lưu ảnh
        $absolutePath = Storage::disk('public')->path($this->imagePath);
        $imageData = file_get_contents($absolutePath);
        $imageName = Str::uuid() . '.webp';

        $image = Storage::disk('public')->put('images/' . $imageName, $imageData);
        $img = Image::create([
            'img_name' => $imageName,
            'img_path' => 'images/' . $imageName,
            'category' => 'song_img',
        ]);
        // Xóa file tạm
        Storage::disk('public')->delete($this->imagePath);

        // Lưu audio
        $audio = Storage::disk('public')->get($this->audioPath);

        //Lấy thời lượng bài hát
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze(storage_path('app/public/' . $this->audioPath));
        $duration = isset($fileInfo['playtime_string']) ? $fileInfo['playtime_string'] : null; // "mm:ss" format

        // Kiểm tra bài hát có bản quyền không (Tên và file)
        $status = 'published';
        if ($this->isSimilarSongName($this->data['song_name'])) {
            $status = 'pending';
        }
        if ($this->isCopyrightedAudio($this->audioPath)) {
            $status = 'deleted';
        }

        // Tạo bài hát mới
        $song = new Song();
        $song->song_name = $this->data['song_name'];
        $song->author_id = $this->data['author'];
        $song->area_id = $this->data['area'];
        $song->genre_id = $this->data['genre'];
        $song->audio_path = $this->audioPath;  // Store the path to audio file
        $song->img_id = $img->id;
        // $song->waveform_path = null;
        $song->lyric_path = null;
        $song->status = $status;
        $song->lyric = $this->data['lyric'];
        $song->duration = $duration;

        // Nếu bài hát được duyệt bản quyền
        if ($status === 'published' || $status === 'pending') {
            // Xử lý DASH
            $dashPath = $this->processDash($this->audioPath);
            $song->audio_path = $dashPath; // Lưu đường dẫn file manifest
        }

        $song->save();

        // Gửi thông báo cho người dùng
        $message = match ($status) {
            'deleted' => 'Song is copyrighted and marked as deleted.',
            'pending' => 'Song is suspected of copyright infringement, please wait for review',
            default => 'Song uploaded successfully.',
        };
        if ($status === 'deleted') {
            Log::error("Bài hát '{$song->song_name}' được đánh dấu là bản quyền và đã xóa!");
        } else if ($status === 'pending') {
            Log::warning("Bài hát '{$song->song_name}' đang chờ duyệt bản quyền bởi admin!");
        } else {
            Log::info("Xử lý bài hát '{$song->song_name}' thành công!");
        }
        // Lấy người dùng từ `author_id`
        $user = User::where('author_id', $this->data['author'])->first();
        if ($user) {
            $user->notify(new SongProcessedNotification(
                $song->song_name,
                $status,
                $message
            ));
        }
    }

    private function processDash($audioPath)
    {
        // Chuyển đổi đường dẫn tuyệt đối
        $inputPath = Storage::disk('public')->path($audioPath);
        $outputDir = Storage::disk('public')->path('dash/' . pathinfo($audioPath, PATHINFO_FILENAME));

        // Tạo thư mục nếu nó chưa tồn tại
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Lệnh ffmpeg
        $command = "ffmpeg -i \"$inputPath\" " .
            "-map 0:a:0 -b:a:0 128k -map 0:a:0 -b:a:1 256k -map 0:a:0 -b:a:2 320k " .
            "-f dash \"$outputDir/output.mpd\"";

        // Ghi log lệnh ffmpeg
        // Log::info("FFmpeg Command: $command");

        exec($command . " 2>&1", $output, $returnCode);

        // Kiểm tra lỗi
        if ($returnCode !== 0) {
            Log::error("FFmpeg Error Output: " . implode("\n", $output));
            throw new \Exception("Error processing DASH. Command: $command. Output: " . implode("\n", $output));
        }

        return 'dash/' . pathinfo($audioPath, PATHINFO_FILENAME) . '/output.mpd';
    }

    private function isCopyrightedAudio(string $filePath): bool
    {
        try {
            // Đường dẫn tuyệt đối tới file nhạc
            $absolutePath = storage_path("app/public/$filePath");
            if (!file_exists($absolutePath)) {
                throw new \Exception("File không tồn tại: $filePath");
            }

            // **Trích xuất 200KB đầu của file**
            $fileSize = filesize($absolutePath);
            $chunkSize = 200 * 1024; // 200KB
            $startByte = 0; // Bắt đầu từ đầu file

            $handle = fopen($absolutePath, 'rb');
            fseek($handle, $startByte);
            $data = fread($handle, $chunkSize);
            fclose($handle);

            // Tạo file tạm từ phần dữ liệu đầu
            $chunkFilePath = storage_path("app/public/temp/first200KB.mp3");
            file_put_contents($chunkFilePath, $data);

            // Tạo fingerprint cho phần đầu 200KB
            $outputHead = [];
            exec("fpcalc -json \"$chunkFilePath\"", $outputHead);
            $uploadedFingerprintHead = json_decode(implode('', $outputHead), true);

            // Xóa file tạm
            Storage::disk('public')->delete("temp/first200KB.mp3");

            if (empty($uploadedFingerprintHead) || !isset($uploadedFingerprintHead['fingerprint'])) {
                throw new \Exception("Không thể tạo fingerprint đoạn đầu: $filePath");
            }

            // **Trích xuất 200KB cuối của file**
            $startByte = max(0, $fileSize - $chunkSize); // Bắt đầu từ 200KB cuối
            $handle = fopen($absolutePath, 'rb');
            fseek($handle, $startByte);
            $data = fread($handle, $chunkSize);
            fclose($handle);

            // Tạo file tạm từ phần dữ liệu cuối
            $chunkFilePath = storage_path("app/public/temp/last200KB.mp3");
            file_put_contents($chunkFilePath, $data);

            // Tạo fingerprint cho phần cuối 200KB
            $outputChunk = [];
            exec("fpcalc -json \"$chunkFilePath\"", $outputChunk);
            $uploadedFingerprintChunk = json_decode(implode('', $outputChunk), true);

            // Xóa file tạm
            Storage::disk('public')->delete("temp/last200KB.mp3");

            if (empty($uploadedFingerprintChunk) || !isset($uploadedFingerprintChunk['fingerprint'])) {
                throw new \Exception("Không thể tạo fingerprint đoạn cuối: $filePath");
            }

            // Lấy danh sách file JSON trong thư mục Copyrighted_music
            $copyrightedFiles = Storage::disk('public')->files('Copyrighted_music');
            if (empty($copyrightedFiles)) {
                Log::warning("Thư mục 'Copyrighted_music' không có file nào.");
                return false;
            }

            // So sánh fingerprint
            foreach ($copyrightedFiles as $jsonFile) {
                $jsonContent = Storage::disk('public')->get($jsonFile);
                $copyrightedFingerprint = json_decode($jsonContent, true);

                if (empty($copyrightedFingerprint)) {
                    Log::warning("File JSON không hợp lệ: $jsonFile");
                    continue;
                }

                // So sánh fingerprint đoạn đầu
                $similarityHead = 0;
                if (isset($copyrightedFingerprint['first_200KB_fingerprint'])) {
                    similar_text(
                        $uploadedFingerprintHead['fingerprint'],
                        $copyrightedFingerprint['first_200KB_fingerprint']['fingerprint'] ?? '',
                        $similarityHead
                    );
                }

                // So sánh fingerprint đoạn cuối
                $similarityChunk = 0;
                if (isset($copyrightedFingerprint['last_200KB_fingerprint'])) {
                    similar_text(
                        $uploadedFingerprintChunk['fingerprint'],
                        $copyrightedFingerprint['last_200KB_fingerprint']['fingerprint'] ?? '',
                        $similarityChunk
                    );
                }

                Log::info("----------------------------------------------------------------");
                Log::info("So sánh fingerprint: $jsonFile");
                Log::info("Độ tương đồng đoạn đầu: $similarityHead%");
                Log::info("Độ tương đồng đoạn cuối: $similarityChunk%");

                // Nếu mức độ tương đồng >= 60% cho bất kỳ loại fingerprint nào, coi như vi phạm bản quyền
                if ($similarityHead >= 60 || $similarityChunk >= 60) {
                    Log::info("File nhạc trùng khớp với bản quyền: $jsonFile");
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Lỗi trong isCopyrightedAudio: " . $e->getMessage());
            return false;
        }
    }


    private function isSimilarSongName(string $songName): bool
    {
        try {
            // Kiểm tra file tồn tại trên disk 'public'
            if (!Storage::disk('public')->exists('copyrighted_song_names.json')) {
                throw new \Exception("File 'copyrighted_song_names.json' không tồn tại trong public/storage.");
            }

            // Load nội dung file từ disk 'public'
            $fileContent = Storage::disk('public')->get('copyrighted_song_names.json');

            // Giải mã JSON
            $copyrightedSongs = json_decode($fileContent, true);

            // Kiểm tra JSON hợp lệ
            if (!is_array($copyrightedSongs)) {
                throw new \Exception("Nội dung file không phải là một JSON hợp lệ.");
            }

            // Chuẩn hóa tên bài hát để so sánh
            $songName = strtolower(trim($songName));

            // Check similarity
            foreach ($copyrightedSongs as $copyrightedName) {
                $copyrightedName = strtolower(trim($copyrightedName)); // Chuẩn hóa tên bản quyền

                // Tính khoảng cách Levenshtein
                $distance = levenshtein($songName, $copyrightedName);

                // Ngưỡng dựa trên 30% độ dài tên bản quyền
                $threshold = ceil(strlen($copyrightedName) * 0.3);

                // Nếu khoảng cách nhỏ hơn hoặc bằng ngưỡng, coi là tương đồng
                if ($distance <= $threshold) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Lỗi trong isSimilarSongName: " . $e->getMessage());
            return false;
        }
    }
}
