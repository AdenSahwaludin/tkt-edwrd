<?php

namespace App\Filament\Resources\BarangRusaks;

use App\Filament\Resources\BarangRusaks\Pages\CreateBarangRusak;
use App\Filament\Resources\BarangRusaks\Pages\EditBarangRusak;
use App\Filament\Resources\BarangRusaks\Pages\ListBarangRusaks;
use App\Filament\Resources\BarangRusaks\Schemas\BarangRusakForm;
use App\Filament\Resources\BarangRusaks\Tables\BarangRusaksTable;
use App\Models\BarangRusak;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BarangRusakResource extends Resource
{
    protected static ?string $model = BarangRusak::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Barang Rusak';

    protected static ?string $modelLabel = 'Barang Rusak';

    protected static ?string $pluralModelLabel = 'Barang Rusak';

    protected static string|UnitEnum|null $navigationGroup = 'Master Barang';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('view_barang_rusaks') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return BarangRusakForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BarangRusaksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBarangRusaks::route('/'),
            'create' => CreateBarangRusak::route('/create'),
            'edit' => EditBarangRusak::route('/{record}/edit'),
        ];
    }
}
