<?php

namespace App\Filament\Resources\UrlGroupResource\Pages;

use App\Filament\Resources\UrlGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUrlGroups extends ListRecords
{
    protected static string $resource = UrlGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
