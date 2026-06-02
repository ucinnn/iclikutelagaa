<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HelpdeskMessageResource\Pages;
use App\Models\HelpdeskMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Notifications\Notification; // Pastikan ini di-import

class HelpdeskMessageResource extends Resource
{
    protected static ?string $model = HelpdeskMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getNavigationLabel(): string
    {
        return __('helpdesk.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('helpdesk.navigation_group');
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Buat array yang berisi peran (role) yang diizinkan
        $allowedRoles = ['author', 'admin', 'superadmin'];

        // Periksa apakah peran pengguna saat ini ada di dalam array tersebut
        return in_array(Filament::auth()->user()?->role, $allowedRoles);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('helpdesk.form.section_ticket_info'))
                ->schema([
                    Forms\Components\Placeholder::make('user_info')
                        ->label(__('helpdesk.form.user_label'))
                        ->content(fn(?HelpdeskMessage $record): string => $record?->user?->name ?? '-'),

                    Forms\Components\Placeholder::make('user_email')
                        ->label(__('helpdesk.form.email_label'))
                        ->content(fn(?HelpdeskMessage $record): string => $record?->user?->email ?? '-'),

                    Forms\Components\TextInput::make('subject')
                        ->label(__('helpdesk.form.subject_label'))
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('message')
                        ->label(__('helpdesk.form.message_label'))
                        ->rows(4)
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('status')
                        ->label(__('helpdesk.form.status_label'))
                        ->options([
                            'open' => __('helpdesk.form.status_options.open'),
                            'closed' => __('helpdesk.form.status_options.closed'),
                        ])
                        ->required()
                        ->live()
                        ->helperText(__('helpdesk.form.status_helper')),
                ])
                ->columns(2),

            Forms\Components\Section::make(__('helpdesk.form.section_conversation'))
                ->description(__('helpdesk.form.conversation_description'))
                ->schema([
                    Forms\Components\ViewField::make('conversation_thread')
                        ->label('')
                        ->view('filament.forms.components.conversation-thread')
                        ->columnSpanFull(),
                ])
                ->visible(fn(?HelpdeskMessage $record) => $record && $record->replies->count() > 0)
                ->collapsible()
                ->collapsed(false),

                  Forms\Components\Section::make(__('helpdesk.form.section_reply'))
                ->description(__('helpdesk.form.reply_description'))
                ->schema([
                    Forms\Components\Textarea::make('admin_reply')
                        ->label(__('helpdesk.form.admin_reply_label'))
                        ->rows(6)
                        ->placeholder(__('helpdesk.form.admin_reply_placeholder'))
                        ->helperText(__('helpdesk.form.admin_reply_helper'))
                        ->columnSpanFull()
                        ->required(false),

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('send_reply')
                            ->label('Kirim Balasan')
                            ->icon('heroicon-o-paper-airplane')
                            ->color('primary')
                            ->action(function ($record, Forms\Get $get, $livewire) {

                                if (method_exists($livewire, 'save')) {
                                    $livewire->save();
                                }

                                $replyText = $get('admin_reply');

                                if (blank($replyText)) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Pesan balasan tidak boleh kosong.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $reply = \App\Models\HelpdeskMessage::create([
                                    'user_id'        => auth()->id(),
                                    'parent_id'      => $record->id,
                                    'subject'        => null,
                                    'message'        => $replyText,
                                    'is_admin_reply' => true,
                                    'status'         => $record->status,
                                ]);

                                $record->update(['is_replied' => true]);

                                // ✅ Kirim email ke user
                                $ticketUser = $record->user;
                                if ($ticketUser && $ticketUser->email) {
                                    \Illuminate\Support\Facades\Mail::to($ticketUser->email)
                                        ->send(new \App\Mail\HelpdeskTicketReplied($record, $reply));
                                }

                                \Filament\Notifications\Notification::make()
                                    ->title('Balasan berhasil dikirim.')
                                    ->success()
                                    ->send();

                                return redirect(\App\Filament\Resources\HelpdeskMessageResource::getUrl('index'));
                            }),
                    ])
                ])
                ->visible(fn(?HelpdeskMessage $record) => $record && $record->status === 'open'),
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->mainThreads())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label(__('helpdesk.table.no_label'))
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('helpdesk.table.user_label'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn(HelpdeskMessage $record): string => $record->user->email ?? ''),

                Tables\Columns\TextColumn::make('subject')
                    ->label(__('helpdesk.table.subject_label'))
                    ->searchable()
                    ->tooltip(fn($record) => $record->subject)
                    ->formatStateUsing(fn($state) => match($state) {
                        'HR'   => '<span style="display:inline-flex;align-items:center;gap:6px;background:#dbeafe;color:#1d4ed8;padding:4px 10px;border-radius:999px;font-size:13px;font-weight:600;"><i class="fas fa-users" style="font-size:12px;"></i> HR</span>',
                        'HSE'  => '<span style="display:inline-flex;align-items:center;gap:6px;background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:999px;font-size:13px;font-weight:600;"><i class="fas fa-hard-hat" style="font-size:12px;"></i> HSE</span>',
                        'Umum' => '<span style="display:inline-flex;align-items:center;gap:6px;background:#fef9c3;color:#a16207;padding:4px 10px;border-radius:999px;font-size:13px;font-weight:600;"><i class="fas fa-comment-dots" style="font-size:12px;"></i> Umum</span>',
                        default => '<span style="display:inline-flex;align-items:center;gap:6px;background:#f3f4f6;color:#374151;padding:4px 10px;border-radius:999px;font-size:13px;font-weight:600;"><i class="fas fa-tag" style="font-size:12px;"></i> ' . e($state) . '</span>',
                    })
                    ->html(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('helpdesk.table.status_label'))
                    ->colors([
                        'success' => 'open',
                        'danger' => 'closed',
                    ])
                    ->icons([
                        'heroicon-o-lock-closed' => 'closed',
                        'heroicon-o-chat-bubble-left-right' => 'open',
                    ])
                    // Opsi label kustom jika diperlukan, jika tidak, akan menggunakan 'open'/'closed'
                    ->formatStateUsing(fn(string $state) => __("helpdesk.form.status_options.{$state}")),


                Tables\Columns\TextColumn::make('is_replied')
                    ->label(__('helpdesk.table.replied_label'))
                    ->formatStateUsing(fn($state) => $state
                        ? '<span style="display:inline-flex;align-items:center;gap:6px;background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="fas fa-check-circle"></i> Sudah Dibalas</span>'
                        : '<span style="display:inline-flex;align-items:center;gap:6px;background:#fee2e2;color:#dc2626;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="fas fa-clock"></i> Belum Dibalas</span>'
                    )
                    ->html(),

                Tables\Columns\TextColumn::make('replies_count')
                    ->label(__('helpdesk.table.messages_label'))
                    ->counts([
                        'replies' => fn ($query) => $query->where('is_admin_reply', false),
                    ])
                    ->formatStateUsing(function ($state, $record) {
                        // tambah 1 untuk pesan pertama dari user
                        return $state + ($record->is_admin_reply ? 0 : 1);
                    })
                    ->badge()
                    ->color('info')
                    ->tooltip(__('helpdesk.table.messages_tooltip')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('helpdesk.table.created_label'))
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(fn($record) => $record->created_at->diffForHumans()),
            ])
            
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open'   => __('helpdesk.filter.status_options.open'),
                        'closed' => __('helpdesk.filter.status_options.closed'),
                    ]),
            
                Tables\Filters\SelectFilter::make('subject')
                    ->label('Topik')
                    ->options([
                        'HR'   => 'HR',
                        'HSE'  => 'HSE',
                        'Umum' => 'Umum',
                    ]),
            
                Tables\Filters\Filter::make('unresponded')
                    ->label(__('helpdesk.filter.unresponded_label'))
                    ->query(fn($query) => $query->whereDoesntHave('replies', fn($q) => $q->where('is_admin_reply', true))),
            
                Tables\Filters\Filter::make('created_at')
                    ->label('Rentang Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->displayFormat('d M Y')
                            ->native(false),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d M Y')
                            ->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'],  fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'])  $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['from'])->format('d M Y');
                        if ($data['until']) $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['until'])->format('d M Y');
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('helpdesk.action.view_reply_label'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success'),

                Tables\Actions\Action::make('close_ticket')
                    ->label(__('helpdesk.action.close_label'))
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'open')
                    ->modalHeading(__('helpdesk.action.close_modal_heading'))
                    ->modalDescription(__('helpdesk.action.close_modal_description'))
                    ->action(function ($record) {
                        $record->update(['status' => 'closed']);

                        Notification::make()
                            ->title(__('helpdesk.notification.close_success_title'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reopen_ticket')
                    ->label(__('helpdesk.action.reopen_label'))
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'closed')
                    ->modalHeading(__('helpdesk.action.reopen_modal_heading'))
                    ->modalDescription(__('helpdesk.action.reopen_modal_description'))
                    ->action(function ($record) {
                        $record->update(['status' => 'open']);

                        Notification::make()
                            ->title(__('helpdesk.notification.reopen_success_title'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => in_array(Filament::auth()->user()->role, ['admin', 'superadmin'])),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('close_tickets')
                        ->label(__('helpdesk.bulk_action.close_label'))
                        ->icon('heroicon-o-lock-closed')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn($records) => $records->each->update(['status' => 'closed']))
                        ->visible(fn() => in_array(Filament::auth()->user()->role, ['admin', 'superadmin'])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHelpdeskMessages::route('/'),
            'edit' => Pages\EditHelpdeskMessage::route('/{record}/edit'),
        ];
    }
}