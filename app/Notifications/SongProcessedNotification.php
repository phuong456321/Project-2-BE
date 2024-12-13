<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SongProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $songName;
    public $status;
    public $message;
    /**
     * Create a new notification instance.
     */
    public function __construct($songName, $status, $message)
    {
        $this->songName = $songName;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Thông báo xử lý bài hát')
            ->line("Bài hát '{$this->songName}' đã được xử lý.")
            ->line($this->message)
            ->action('Xem chi tiết', url('/songs/' . $this->songName))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Lưu thông báo vào cơ sở dữ liệu.
     */
    public function toDatabase($notifiable)
    {
        return [
            'song_name' => $this->songName,
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
