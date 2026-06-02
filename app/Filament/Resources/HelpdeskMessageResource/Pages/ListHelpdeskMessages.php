<?php

namespace App\Filament\Resources\HelpdeskMessageResource\Pages;

use App\Filament\Resources\HelpdeskMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use Carbon\Carbon;

class ListHelpdeskMessages extends ListRecords
{
    protected static string $resource = HelpdeskMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),

            /*
            |--------------------------------
            | EXPORT DATA TABEL
            |--------------------------------
            */

            ExportAction::make()
                ->label('Export Helpdesk')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('secondary')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename('helpdesk-' . date('Y-m-d'))
                        ->withColumns([

                            Column::make('no')
                                ->heading('No')
                                ->getStateUsing(function () {
                                    static $no = 0;
                                    return ++$no;
                                }),

                            Column::make('user.name')
                                ->heading('Pengirim'),

                            Column::make('subject')
                                ->heading('Topik'),

                            Column::make('message')
                                ->heading('Pesan Awal')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? html_entity_decode(strip_tags($state)) : '-'
                                ),

                            Column::make('status')
                                ->heading('Status')
                                ->formatStateUsing(fn ($state) => match ($state) {
                                    'open' => 'Terbuka',
                                    'closed' => 'Ditutup',
                                    default => $state,
                                }),

                            Column::make('is_replied')
                                ->heading('Balasan')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? 'Sudah Dibalas' : 'Belum Dibalas'
                                ),

                            Column::make('created_at')
                                ->heading('Dibuat')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? Carbon::parse($state)->format('d M Y H:i') : '-'
                                ),
                        ]),
                ]),

            /*
            |--------------------------------
            | EXPORT SEMUA PESAN (THREAD)
            |--------------------------------
            */

            ExportAction::make('export_messages')
                ->label('Export Percakapan Tiket')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->exports([
                    ExcelExport::make()
                        ->fromModel()
                        ->modifyQueryUsing(fn ($query) =>
                            $query->whereNull('parent_id')->with(['replies.user', 'user'])
                        )
                        ->withFilename('helpdesk-conversation-' . date('Y-m-d'))
                        ->withColumns([

                            Column::make('id')
                                ->heading('Ticket ID'),

                            Column::make('user.name')
                                ->heading('Nama User'),

                            Column::make('user.email')
                                ->heading('Email User'),

                            Column::make('subject')
                                ->heading('Topik'),

                            Column::make('message')
                                ->heading('Pesan Awal')
                                ->getStateUsing(fn ($record) => html_entity_decode(strip_tags($record->message))),

                            Column::make('conversation')
                                ->heading('Percakapan')
                                ->getStateUsing(function ($record) {

                                    $conversation = [];

                                    // pesan pertama
                                    $conversation[] =
                                        "[User - {$record->created_at->format('d M Y H:i')}]\n" .
                                        html_entity_decode(strip_tags($record->message));

                                    // balasan
                                    foreach ($record->replies as $reply) {

                                        $sender = $reply->is_admin_reply
                                            ? 'Admin'
                                            : ($reply->user->name ?? 'User');

                                        $conversation[] =
                                            "\n[{$sender} - {$reply->created_at->format('d M Y H:i')}]\n" .
                                            html_entity_decode(strip_tags($reply->message));
                                    }

                                    return implode("\n----------------------\n", $conversation);
                                }),

                            Column::make('created_at')
                                ->heading('Dibuat')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? Carbon::parse($state)->format('d M Y H:i') : '-'
                                ),
                        ])
                        ->only(['id', 'user.name', 'user.email', 'subject', 'message', 'conversation', 'created_at']),
                ]),
        ];
    }
}