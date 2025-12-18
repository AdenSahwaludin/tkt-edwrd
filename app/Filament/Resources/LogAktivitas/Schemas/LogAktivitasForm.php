<?php

namespace App\Filament\Resources\LogAktivitas\Schemas;

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
                    ->required(false),

                TextInput::make('user.name')
                    ->label('User')
                    ->disabled()
                    ->required(false),

                TextInput::make('created_at')
                    ->label('Tanggal')
                    ->disabled()
                    ->required(false),

                TextInput::make('ip_address')
                    ->label('IP Address')
                    ->disabled()
                    ->required(false),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->disabled()
                    ->rows(3)
                    ->required(false),

                Textarea::make('perubahan_data')
                    ->label('Perubahan Data (JSON)')
                    ->disabled()
                    ->rows(4)
                    ->required(false)
                    ->columnSpanFull(),
            ]);
    }
}
