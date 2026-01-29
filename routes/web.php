<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlipPrintController;
use App\Http\Controllers\PdfMakerController;
use App\Http\Services\PiwapiService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/slips/{slip}/print', SlipPrintController::class)
    ->name('slips.print');

Route::get('/slips/draft/pdf', [PdfMakerController::class, 'downloadDraft'])
    ->name('slips.draft.pdf');

Route::get('/whatsapp/test', function () {
    $service = app(PiwapiService::class);
    $res = $service
        ->setRecipient('087857580910')
        ->data([
            'name' => 'John Doe',
        ])->message('Halo {{name}}')
        ->document(url('storage/SLIP-GAJI-andi-saputra.pdf'), 'pdf')
        ->send();
    return $res;
});
