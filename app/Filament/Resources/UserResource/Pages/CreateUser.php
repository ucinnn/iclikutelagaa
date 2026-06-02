<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Notifications\UserRegisteredEmail;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\User;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Override the default record creation process to insert custom logic.
     *
     * @param array $data The validated form data.
     * @return Model The newly created user model.
     */
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Store the original plain text password before hashing.
        // This step is secure as this method runs after form validation.
        $plainPassword = $data['password'];

        // 2. Hash the password for database storage.
        $data['password'] = Hash::make($plainPassword);

        // 3. Set the 'updated_by' field automatically.
        $data['updated_by'] = Filament::auth()->user()?->name ?? 'System';

        // 4. Create the new user record in the database.
        $user = static::getModel()::create($data);

        // 5. Send an email notification containing the plain text password.
        try {
            // We use the $plainPassword variable stored in step 1.
            $user->notify(new UserRegisteredEmail($plainPassword));
            Log::info("User registration notification sent successfully to {$user->email}");

            // Display a SUCCESS pop-up notification
            Notification::make()
                ->title('Notification Sent')
                ->body("The registration email has been successfully sent to {$user->email}.")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            // Log the error if the notification fails to send.
            Log::error("Failed to send registration notification for {$user->email}: {$e->getMessage()}");

            // Display a FAILED pop-up notification
            Notification::make()
                ->title('Failed to Send Notification')
                ->body("Failed to send registration email to {$user->email}. Please check the logs for details.")
                ->danger()
                ->send();
        }

        $user = static::getModel()::create($data);

        // Kirim notifikasi ke admin
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($adminUsers as $admin) {
            // kirim notifikasi email & database Laravel
            $admin->notify(new UserRegisteredEmail($data['password'] ?? '******'));

            // kirim ke panel Filament 🔔
            FilamentNotification::make()
                ->title('Akun baru terdaftar')
                ->body("User baru dengan nama **{$user->name}** telah didaftarkan.")
                ->success()
                ->sendToDatabase($admin);
        }

        // 6. Return the newly created user model.
        return $user;
    }

    protected function getRedirectUrl(): string
    {
        // Using getUrl() from the resource for a consistent URL.
        return static::getResource()::getUrl('index');
    }
}
