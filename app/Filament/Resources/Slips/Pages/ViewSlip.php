<?php

namespace App\Filament\Resources\Slips\Pages;

use App\Filament\Resources\Slips\SlipResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSlip extends ViewRecord
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
