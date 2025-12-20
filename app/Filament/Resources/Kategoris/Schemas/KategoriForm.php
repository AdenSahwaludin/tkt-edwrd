<?php

namespace App\Filament\Resources\Kategoris\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                        TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: Elektronik, Furniture, ATK'),

                        Textarea::make('deskripsi')
                            ->label('Keterangan')
                            ->maxLength(65535)
                            ->rows(4)
                            ->placeholder('Deskripsi kategori (opsional)'),
            ]);
    }
}
