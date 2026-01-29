<?php

namespace App\Filament\Resources\Karyawans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KaryawanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('position'),
                TextEntry::make('phone'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('address'),
                TextEntry::make('salary')
                    ->numeric(),
                TextEntry::make('method_payment'),
                TextEntry::make('bank_account_name')
                    ->placeholder('-'),
                TextEntry::make('bank_account_number')
                    ->placeholder('-'),
                TextEntry::make('bank_name')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
