<?php

namespace App\Filament\Resources\Karyawans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

class KaryawanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('bank_account_name', $state);
                    }),
                TextInput::make('position')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('salary')
                    ->required()
                    ->numeric(),
                Select::make('method_payment')
                    ->required()
                    ->options([
                        'transfer' => 'Transfer',
                        'ewallet' => 'E-Wallet',
                        'cash' => 'Cash',
                    ]),
                TextInput::make('bank_account_name')->label('Bank Account Holder / E-Wallet Name'),
                TextInput::make('bank_account_number')->label('Bank Account Number / E-Wallet Number'),
                TextInput::make('bank_name')->label('Bank Name / E-Wallet Name')->datalist([
                    'BANK CENTRAL ASIA ( BCA )',
                    'BANK MANDIRI',
                    'BANK NEGARA INDONESIA ( BNI )',
                    'BANK RAKYAT INDONESIA ( BRI )',
                    'BANK SYARIAH INDONESIA ( BSI )',
                    'BANK CIMB NIAGA',
                    'BANK PERMATA',
                    'BANK DANAMON',
                    'BANK BTPN',
                    'BANK OCBC NISP',
                    'BANK MAYBANK',
                    'BANK PANIN',
                    'BANK SINARMAS',
                    'BANK MEGA',
                    'BANK BUKOPIN',
                    'BANK BJB',
                    'BANK BPD',
                    'BANK BPD',

                    'OVO',
                    'GOPAY',
                    'DANA',
                    'SHOPEEPAY',
                    'LINKAJA',
                    'BANK JAGO',
                ]),
            ]);
    }
}
