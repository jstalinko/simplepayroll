<?php

namespace App\Filament\Resources\Slips\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SlipInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('karyawan_id')
                    ->numeric(),
                TextEntry::make('main_salary')
                    ->numeric(),
                TextEntry::make('overtime_pay')
                    ->numeric(),
                TextEntry::make('meal_pay')
                    ->numeric(),
                TextEntry::make('transportation_pay')
                    ->numeric(),
                TextEntry::make('bonus')
                    ->numeric(),
                TextEntry::make('bonus_description')
                    ->placeholder('-'),
                TextEntry::make('late_deduction')
                    ->numeric(),
                TextEntry::make('absent_deduction')
                    ->numeric(),
                TextEntry::make('break_stuff_deduction')
                    ->numeric(),
                TextEntry::make('other_deduction')
                    ->numeric(),
                TextEntry::make('other_deduction_description')
                    ->placeholder('-'),
                TextEntry::make('total_salary')
                    ->numeric(),
                TextEntry::make('total_deduction')
                    ->numeric(),
                TextEntry::make('total_net_salary')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
