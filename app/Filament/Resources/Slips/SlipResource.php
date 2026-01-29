<?php

namespace App\Filament\Resources\Slips;

use App\Filament\Resources\Slips\Pages\CreateSlip;
use App\Filament\Resources\Slips\Pages\EditSlip;
use App\Filament\Resources\Slips\Pages\ListSlips;
use App\Filament\Resources\Slips\Pages\ViewSlip;
use App\Filament\Resources\Slips\Schemas\SlipForm;
use App\Filament\Resources\Slips\Schemas\SlipInfolist;
use App\Filament\Resources\Slips\Tables\SlipsTable;
use App\Models\Slip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SlipResource extends Resource
{
    protected static ?string $model = Slip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Newspaper;

    public static function form(Schema $schema): Schema
    {
        return SlipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SlipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlipsTable::configure($table);
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
            'index' => ListSlips::route('/'),
            'create' => CreateSlip::route('/create'),
            'view' => ViewSlip::route('/{record}'),
            'edit' => EditSlip::route('/{record}/edit'),
        ];
    }
}
