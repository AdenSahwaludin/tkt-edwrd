<?php

namespace App\Filament\Resources\LogAktivitas\Pages;

use App\Filament\Resources\LogAktivitas\LogAktivitasResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewLogAktivitas extends ViewRecord
{
    protected static string $resource = LogAktivitasResource::class;

    public function getContentSchema(): Schema
    {
        return Schema::make()
            ->schema([
                Section::make('Informasi Aktivitas')
                    ->schema([
                        Group::make()
                            ->schema([
                                Placeholder::make('jenis_aktivitas')
                                    ->label('Jenis Aktivitas')
                                    ->content(fn ($record) => strtoupper($record->jenis_aktivitas)),

                                Placeholder::make('user')
                                    ->label('User')
                                    ->content(fn ($record) => $record->user ? $record->user->name.' ('.$record->user->email.')' : 'Sistem/Tidak Diketahui'),

                                Placeholder::make('tanggal')
                                    ->label('Tanggal')
                                    ->content(fn ($record) => $record->created_at->format('d F Y, H:i:s')),

                                Placeholder::make('ip_address')
                                    ->label('IP Address')
                                    ->content(fn ($record) => $record->ip_address ?? '-'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Detail Perubahan')
                    ->schema([
                        Placeholder::make('nama_tabel')
                            ->label('Tabel')
                            ->content(fn ($record) => $record->nama_tabel ? ucwords(str_replace('_', ' ', $record->nama_tabel)) : '-'),

                        Placeholder::make('record_id')
                            ->label('Record ID')
                            ->content(fn ($record) => $record->record_id ?? '-'),

                        Placeholder::make('deskripsi')
                            ->label('Deskripsi')
                            ->content(fn ($record) => $record->deskripsi)
                            ->columnSpanFull(),
                    ]),

                Section::make('Perubahan Data (JSON)')
                    ->schema([
                        Placeholder::make('perubahan_data')
                            ->label('')
                            ->content(function ($record) {
                                if (! $record->perubahan_data) {
                                    return 'Tidak ada data perubahan';
                                }

                                return view('components.json-viewer', [
                                    'data' => $record->perubahan_data,
                                ]);
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Informasi Browser')
                    ->schema([
                        Placeholder::make('user_agent')
                            ->label('User Agent')
                            ->content(fn ($record) => $record->user_agent ?? '-')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
