<?php

namespace App\Filament\Resources\TransaksiKeluars;

use App\Filament\Resources\TransaksiKeluars\Pages\CreateTransaksiKeluar;
use App\Filament\Resources\TransaksiKeluars\Pages\EditTransaksiKeluar;
use App\Filament\Resources\TransaksiKeluars\Pages\ListTransaksiKeluars;
use App\Filament\Resources\TransaksiKeluars\Pages\ViewTransaksiKeluar;
use App\Filament\Resources\TransaksiKeluars\Schemas\TransaksiKeluarForm;
use App\Filament\Resources\TransaksiKeluars\Tables\TransaksiKeluarsTable;
use App\Models\TransaksiKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TransaksiKeluarResource extends Resource
{
    protected static ?string $model = TransaksiKeluar::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-right-circle';

    protected static ?string $navigationLabel = 'Transaksi Keluar';

    protected static ?string $modelLabel = 'Transaksi Keluar';

    protected static ?string $pluralModelLabel = 'Transaksi Keluar';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('view_transaksi_keluars') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return TransaksiKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransaksiKeluarsTable::configure($table);
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
            'index' => ListTransaksiKeluars::route('/'),
            'create' => CreateTransaksiKeluar::route('/create'),
            'view' => ViewTransaksiKeluar::route('/{record}'),
            'edit' => EditTransaksiKeluar::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
