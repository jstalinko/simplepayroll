<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;

class Setting extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.setting';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    public ?array $data = [];

    public function mount(): void
    {
        // penting: fill() biar schema kebentuk & state ke-transform
        $this->form->fill($this->readSettings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Whatsapp API')
                    ->schema([
                        TextInput::make('whatsapp.piwapi_account_id')
                            ->label('Piwapi Account ID')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('whatsapp.piwapi_secret')
                            ->label('Piwapi Secret')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('General')
                    ->schema([
                        FileUpload::make('general.company_logo')
                            ->label('Company Logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->helperText('Disimpan di storage/app/public/settings (public).'),
                        TextInput::make('general.company_name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('general.company_address')
                            ->label('Company Address')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        TextInput::make('general.company_phone')
                            ->label('Company Phone')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        TextInput::make('general.company_email')
                            ->label('Company Email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Select::make('general.payday_date')
                            ->label('Payday Date')
                            ->required()
                            ->options(collect(range(1, 31))->mapWithKeys(fn($d) => [$d => (string) $d])->all())
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState(); // <- ambil state yang sudah divalidasi

        try {
            Storage::disk('local')->put(
                'settings.json',
                json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            Notification::make()
                ->title('Settings saved')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Failed to save settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function readSettings(): array
    {
        if (! Storage::disk('local')->exists('settings.json')) {
            return [
                'whatsapp' => [
                    'piwapi_account_id' => '',
                    'piwapi_secret' => '',
                ],
                'general' => [
                    'company_logo' => null,
                    'company_name' => '',
                    'company_address' => '',
                    'company_phone' => '',
                    'company_email' => '',
                    'payday_date' => 25,
                ],
            ];
        }

        $json = json_decode(Storage::disk('local')->get('settings.json'), true);

        return is_array($json) ? $json : [];
    }
}
