<?php

namespace App\Filament\Resources\Slips\Pages;

use App\Filament\Resources\Slips\SlipResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSlip extends EditRecord
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
