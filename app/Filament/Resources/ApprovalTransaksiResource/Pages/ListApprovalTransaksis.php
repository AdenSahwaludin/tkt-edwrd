<?php

namespace App\Filament\Resources\ApprovalTransaksiResource\Pages;

use App\Filament\Resources\ApprovalTransaksiResource;
use Filament\Resources\Pages\ListRecords;

class ListApprovalTransaksis extends ListRecords
{
    protected static string $resource = ApprovalTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
