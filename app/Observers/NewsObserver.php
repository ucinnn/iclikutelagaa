<?php
// app/Observers/NewsObserver.php
namespace App\Observers;

use App\Models\News;
use App\Models\User;
use App\Notifications\NewsPublishedNotification;

class NewsObserver
{
    public function updated(News $news)
    {
        if ($news->getOriginal('status') === 'scheduled' && $news->status === 'published') {
            $recipients = User::whereIn('role', ['superadmin', 'admin', 'author'])->get();

            foreach ($recipients as $user) {
                $user->notify(new NewsPublishedNotification($news));
            }
        }
    }
}
