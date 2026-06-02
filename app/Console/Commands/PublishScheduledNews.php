<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Carbon\Carbon;
use App\Notifications\NewsPublishedNotification;
use App\Models\User;

class PublishScheduledNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan news:publish-scheduled
     */
    protected $signature = 'news:publish-scheduled';

    /**
     * The console command description.
     */
    protected $description = 'Publishes all scheduled news items whose publish time has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $now = Carbon::now();

        $scheduledNews = News::where('status', 'schedule')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->get();

        if ($scheduledNews->isEmpty()) {
            $this->info('No scheduled news to publish.');
            return 0;
        }


        foreach ($scheduledNews as $news) {
            $news->update([
                'status' => 'published',
            ]);

            // Kirim notifikasi ke admin (atau semua user tertentu)
            $admins = User::whereIn('role', ['admin', 'superadmin', 'author'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewsPublishedNotification($news));
            }

            $this->info("Published: {$news->title} ({$news->published_at})");
        }
        $this->info('All eligible scheduled news have been published.');
        return 0;
    }
}
