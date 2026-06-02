<?php

namespace App\Filament\Resources\HelpdeskMessageResource\Pages;

use App\Filament\Resources\HelpdeskMessageResource;
use App\Models\HelpdeskMessage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EditHelpdeskMessage extends EditRecord
{
    protected static string $resource = HelpdeskMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
        
    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('batal')
                ->label('Batal')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika admin mengirim balasan baru
        if (!empty($data['admin_reply'])) {

            // Simpan balasan sebagai record baru
            HelpdeskMessage::create([
                'user_id' => Auth::id(),
                'parent_id' => $this->record->id,
                'subject' => null,
                'message' => $data['admin_reply'],
                'is_admin_reply' => true,
                'status' => $this->record->status,
            ]);

            // Kirim email ke user
            $this->sendEmailToUser($data['admin_reply']);

            // Hapus admin_reply dari data agar tidak disimpan di record utama
            unset($data['admin_reply']);

            // Notifikasi sukses
            \Filament\Notifications\Notification::make()
                ->title('Reply sent successfully')
                ->success()
                ->body('Email notification has been sent to the user.')
                ->send();
        }

        return $data;
    }

    protected function sendEmailToUser(string $replyMessage): void
    {
        try {
            $user = $this->record->user;
            $subject = $this->record->subject;

            $emailBody = "Hello {$user->name},\n\n";
            $emailBody .= "Our support team has replied to your ticket: {$subject}\n\n";
            $emailBody .= "Admin Reply:\n";
            $emailBody .= "----------------------------------------\n";
            $emailBody .= "{$replyMessage}\n";
            $emailBody .= "----------------------------------------\n\n";
            $emailBody .= "You can reply back through the support portal.\n\n";
            $emailBody .= "Best regards,\n";
            $emailBody .= "Support Team";

            Mail::raw($emailBody, function ($msg) use ($user, $subject) {
                $msg->to($user->email)
                    ->subject("Reply to Ticket: {$subject}");
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());

            \Filament\Notifications\Notification::make()
                ->title('Email failed to send')
                ->warning()
                ->body('Reply saved but email notification failed.')
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Changes saved successfully';
    }
}
