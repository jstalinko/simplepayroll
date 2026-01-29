<?php

namespace App\Filament\Resources\Slips\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use App\Models\Slip;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class SlipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('karyawan.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('main_salary')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('total_salary')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_deduction')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_net_salary')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'draft' => 'warning',
                        default => 'warning',
                    })
                    ->searchable(),


                TextColumn::make('overtime_pay')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meal_pay')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('transportation_pay')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bonus')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bonus_description')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('late_deduction')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('absent_deduction')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('break_stuff_deduction')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('other_deduction')
                    ->numeric()
                    ->money('IDR')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('other_deduction_description')
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
                Action::make('print')
                    ->label('Print PDF')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn(Slip $record) => route('slips.print', $record))
                    ->openUrlInNewTab()->visible(fn(Slip $record) => $record->status === 'paid'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('Mark as Paid')
                        ->action(function (Collection $records) {
                            $records->each(function (Slip $record) {
                                $record->update([
                                    'status' => 'paid',
                                ]);
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->label('Mark as Paid'),

                    BulkAction::make('Paid & Notify Whatsapp')
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id')->all();

                            Slip::whereIn('id', $ids)->update(['status' => 'paid']);

                            $controller = app(\App\Http\Controllers\PdfMakerController::class);
                            $saved = $controller->generateBulkPaid($ids);

                            Notification::make()
                                ->title((string) $saved)
                                ->success()
                                ->send();
                        })

                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->label('Paid & Notify Whatsapp'),
                ]),
            ]);
    }
}
