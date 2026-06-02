<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\News;
use Filament\Notifications\Actions\Action;

class NewsPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function via($notifiable)
    {
        // Kirim ke database dan juga tampilkan via Filament topbar
        return ['database', 'filament'];
    }

    /**
     * Data yang disimpan ke tabel `notifications`
     */
    public function toDatabase($notifiable)
    {
        return [
            'news_id' => $this->news->id,
            'title' => $this->news->title,
            'slug' => $this->news->slug,
            'message' => "Berita '{$this->news->title}' telah diterbitkan pada {$this->news->publish_at}.",
            'url' => route('news.show', $this->news->slug),
        ];
    }

    /**
     * Format notifikasi untuk Filament topbar
     */
    public function toFilament($notifiable)
    {
        return FilamentNotification::make()
            ->title('News Published')
            ->body("Berita **{$this->news->title}** telah diterbitkan pada {$this->news->publish_at}.")
            ->success()
            ->icon('heroicon-o-newspaper')
            ->actions([
                Action::make('view')
                    ->label('Lihat Berita')
                    ->button()
                    ->url(route('news.show', $this->news->slug))
                    ->openUrlInNewTab(),
            ]);
    }

    /**
     * Default array format
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
