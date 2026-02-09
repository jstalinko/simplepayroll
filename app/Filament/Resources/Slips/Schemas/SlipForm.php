<?php

namespace App\Filament\Resources\Slips\Schemas;

use App\Models\Karyawan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;

class SlipForm
{
    private static function recalc(Set $set, Get $get): void
    {
        // Income
        $main  = (int) ($get('main_salary') ?? 0);
        $ot    = (int) ($get('overtime_pay') ?? 0);
        $meal  = (int) ($get('meal_pay') ?? 0);
        $trans = (int) ($get('transportation_pay') ?? 0);
        $bonus = (int) ($get('bonus') ?? 0);

        $totalSalary = $main + $ot + $meal + $trans + $bonus;
        $set('total_salary', $totalSalary);

        // Deduction
        $late  = (int) ($get('late_deduction') ?? 0);
        $abs   = (int) ($get('absent_deduction') ?? 0);
        $break = (int) ($get('break_stuff_deduction') ?? 0);
        $other = (int) ($get('other_deduction') ?? 0);

        $totalDeduction = $late + $abs + $break + $other;
        $set('total_deduction', $totalDeduction);

        // Net
        $set('total_net_salary', $totalSalary - $totalDeduction);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('karyawan_id')
                ->label('Karyawan')
                ->relationship('karyawan', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->native(false)
                ->live() // v4: bikin reactive update
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $karyawan = $state ? Karyawan::find($state) : null;
                    $set('main_salary', (int) ($karyawan?->salary ?? 0));

                    self::recalc($set, $get);
                })
                ->columnSpanFull(),


            Section::make('Periode')->schema([
                DatePicker::make('period_start')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(fn() => now()->subMonth()->startOfMonth()),

                DatePicker::make('period_end')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->default(fn() => now()->subMonth()->endOfMonth()),
            ])->columns(2)->columnSpanFull(),

            Section::make('Pendapatan')->schema([
                TextInput::make('main_salary')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('overtime_pay')
                    ->label('Gaji Lembur')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('meal_pay')
                    ->label('Uang Makan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('transportation_pay')
                    ->label('Uang Transport')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('bonus')
                    ->label('Bonus')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('bonus_description')
                    ->label('Keterangan Bonus'),
            ]),

            Section::make('Potongan')->schema([
                TextInput::make('late_deduction')
                    ->label('Potongan Keterlambatan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('absent_deduction')
                    ->label('Potongan Absensi')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('break_stuff_deduction')
                    ->label('Potongan Barang Rusak')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('other_deduction')
                    ->label('Potongan Lainnya')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn(Set $set, Get $get) => self::recalc($set, $get)),

                TextInput::make('other_deduction_description')
                    ->label('Keterangan Potongan Lainnya'),
            ]),

            TextInput::make('total_salary')
                ->label('Total Pendapatan')
                ->required()
                ->numeric()
                ->readOnly()
                ->prefix('Rp')
                ->dehydrated(true) // tetap disimpan ke DB
                ->default(0),

            TextInput::make('total_deduction')
                ->label('Total Potongan')
                ->required()
                ->numeric()
                ->readOnly()
                ->prefix('Rp')
                ->dehydrated(true)
                ->default(0),

            TextInput::make('total_net_salary')
                ->label('Gaji Bersih')
                ->required()
                ->numeric()
                ->readOnly()
                ->prefix('Rp')
                ->dehydrated(true)
                ->default(0),

            Select::make('status')
                ->label('Status')
                ->required()
                ->default('draft')
                ->options([
                    'draft' => 'Draft',
                    'paid' => 'Dibayar',
                    'unpaid' => 'Belum Dibayar',
                ]),
        ]);
    }
}
