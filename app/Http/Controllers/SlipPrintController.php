<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SlipPrintController extends Controller
{
    public function __invoke(Slip $slip)
    {
        // optional: ambil settings company dari storage/app/settings.json
        $settings = [];
        if (Storage::disk('local')->exists('settings.json')) {
            $settings = json_decode(Storage::disk('local')->get('settings.json'), true) ?? [];
        }

        $company = $settings['general'] ?? [];

        $logoPath = null;
        if (! empty($company['company_logo']) && Storage::disk('public')->exists($company['company_logo'])) {
            $logoPath = public_path('storage/' . $company['company_logo']); // absolute path
        }

        $pdf = Pdf::loadView('pdf.slip', [
            'slip' => $slip->load('karyawan'),
            'company' => $company,
            'logoPath' => $logoPath,
        ])->setPaper('A4');


        // stream biar kebuka di tab baru
        return $pdf->stream("slip-gaji-{$slip->id}.pdf");
        // kalau mau langsung download:
        // return $pdf->download("slip-gaji-{$slip->id}.pdf");
    }
}
