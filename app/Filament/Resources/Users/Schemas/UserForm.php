<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pengguna')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Keamanan')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (string $state): string => bcrypt($state)),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Diverifikasi Pada'),
                    ])
                    ->columnSpanFull(),
                Section::make('Peran & Izin')
                    ->schema([
                        MultiSelect::make('roles')
                            ->label('Peran')
                            ->relationship('roles', 'name')
                            ->options(Role::pluck('name', 'id'))
                            ->helperText('Pilih satu atau lebih peran untuk pengguna ini.')
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
