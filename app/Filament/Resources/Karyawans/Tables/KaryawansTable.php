<?php

namespace App\Filament\Resources\Karyawans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KaryawansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),

                TextColumn::make('salary')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('address')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('method_payment')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_account_name')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_account_number')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_name')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
