<?php

namespace App\Filament\Resources\Lokasis\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LokasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                        TextInput::make('nama_lokasi')
                            ->label('Nama Lokasi')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: Ruang Kepala Sekolah, Lab Komputer'),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(65535)
                            ->rows(4)
                            ->placeholder('Deskripsi lokasi (opsional)'),
            ]);
    }
}
