<?php

namespace App\Filament\Resources\LogAktivitas\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LogAktivitasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('jenis_aktivitas')
                    ->label('Jenis Aktivitas')
                    ->disabled()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                Placeholder::make('user_name')
                    ->label('User')
                    ->content(fn ($record) => $record->user ? $record->user->name.' ('.$record->user->email.')' : 'Sistem/Tidak Diketahui'),

                Placeholder::make('tanggal')
                    ->label('Tanggal')
                    ->content(fn ($record) => $record->created_at->format('d F Y, H:i:s')),

                TextInput::make('ip_address')
                    ->label('IP Address')
                    ->disabled()
                    ->placeholder('Tidak ada IP'),

                Placeholder::make('nama_tabel_display')
                    ->label('Tabel')
                    ->content(fn ($record) => $record->nama_tabel ? ucwords(str_replace('_', ' ', $record->nama_tabel)) : '-'),

                Placeholder::make('record_id')
                    ->label('Record ID')
                    ->content(fn ($record) => $record->record_id ?? '-'),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->disabled()
                    ->rows(3)
                    ->columnSpanFull(),

                Placeholder::make('perubahan_data_json')
                    ->label('Perubahan Data (JSON)')
                    ->content(function ($record) {
                        if (! $record->perubahan_data) {
                            return 'Tidak ada data perubahan';
                        }

                        return view('components.json-viewer', [
                            'data' => $record->perubahan_data,
                        ]);
                    })
                    ->columnSpanFull(),

                Textarea::make('user_agent')
                    ->label('User Agent')
                    ->disabled()
                    ->rows(2)
                    ->placeholder('Tidak ada data')
                    ->columnSpanFull(),
            ]);
    }
}
