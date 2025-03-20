<?php

namespace App\Filament\Resources\UrlGroupResource\Pages;

use App\Filament\Resources\UrlGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUrlGroup extends EditRecord
{
    protected static string $resource = UrlGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
