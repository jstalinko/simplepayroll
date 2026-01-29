<?php

namespace App\Filament\Resources\Slips\Pages;

use App\Filament\Resources\Slips\SlipResource;
use App\Models\Slip;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;

class ListSlips extends ListRecords
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('downloadDraft')
                ->label('Download Draft')
                ->icon('heroicon-m-cloud-arrow-down')
                ->color('success')
                ->url(fn() => route('slips.draft.pdf'))
                ->openUrlInNewTab()

        ];
    }
}
