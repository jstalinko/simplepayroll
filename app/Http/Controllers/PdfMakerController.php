<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfMakerController extends Controller
{
    public function downloadDraft()
    {
        $slips = Slip::query()
            ->where('status', 'draft')
            ->with('karyawan')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.draft', [
            'slips' => $slips,
            'generatedAt' => now(),
        ])->setPaper('A4', 'portrait');

        return $pdf->download('slips-draft-' . now()->format('Y-m-d_His') . '.pdf');
    }
    public function generateBulkPaid($ids = [])
    {
        $settings = [];
        if (Storage::disk('local')->exists('settings.json')) {
            $settings = json_decode(Storage::disk('local')->get('settings.json'), true) ?? [];
        }

        $company = $settings['general'] ?? [];

        $logoPath = null;
        if (! empty($company['company_logo']) && Storage::disk('public')->exists($company['company_logo'])) {
            $logoPath = public_path('storage/' . $company['company_logo']); // absolute path
        }

        $slips = Slip::where('status', 'paid')->with('karyawan')->whereIn('id', $ids)->get();

        $save = [];
        foreach ($slips as $slip) {
            $pdf = Pdf::loadView('pdf.slip', [
                'slip' => $slip,
                'company' => $company,
                'logoPath' => $logoPath,
            ])->setPaper('A4');

            $slipName = "SLIP-GAJI-" . str()->slug($slip->karyawan->name);
            $pdf->save(storage_path('app/public/' . $slipName . '.pdf'));
            $save[$slip->id]['path'] = url('storage/' . $slipName . '.pdf');
            $save[$slip->id]['name'] = $slipName;
        }
        return $save;
    }
}
