<?php

namespace App\Filament\Widgets;

use App\Models\Karyawan;
use App\Models\Slip;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();

        $totalKaryawan = Karyawan::count();
        $totalGajiBulanan = (int) Karyawan::sum('salary');

        // Bulan ini
        $bulanIni = (int) Slip::query()
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_net_salary');

        // Tahun ini
        $tahunIni = (int) Slip::query()
            ->whereYear('created_at', $now->year)
            ->sum('total_net_salary');

        // Bulan lalu
        $prevMonth = $now->copy()->subMonth();
        $bulanLalu = (int) Slip::query()
            ->whereMonth('created_at', $prevMonth->month)
            ->whereYear('created_at', $prevMonth->year)
            ->sum('total_net_salary');

        // Tahun lalu
        $prevYear = $now->copy()->subYear();
        $tahunLalu = (int) Slip::query()
            ->whereYear('created_at', $prevYear->year)
            ->sum('total_net_salary');

        // Trend 12 bulan terakhir (sparkline)
        $trend12Bulan = collect(range(11, 0))
            ->map(function (int $i) use ($now) {
                $d = $now->copy()->subMonths($i);

                return (int) Slip::query()
                    ->whereMonth('created_at', $d->month)
                    ->whereYear('created_at', $d->year)
                    ->sum('total_net_salary');
            })
            ->values()
            ->all();

        $bulanDiff = $this->pctDiff($bulanIni, $bulanLalu);
        $tahunDiff = $this->pctDiff($tahunIni, $tahunLalu);

        return [
            Stat::make('Total Karyawan', Number::format($totalKaryawan))
                ->description('Jumlah karyawan aktif')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Total Gaji Bulanan', $this->idr($totalGajiBulanan))
                ->description('Akumulasi salary karyawan / bulan')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Pengeluaran Gaji Bulan Ini', $this->idr($bulanIni))
                ->description($this->descCompare('vs bulan lalu', $bulanDiff))
                ->icon('heroicon-o-calendar-days')
                ->color($bulanDiff >= 0 ? 'warning' : 'success')
                ->chart($trend12Bulan),

            Stat::make('Pengeluaran Gaji Tahun Ini', $this->idr($tahunIni))
                ->description($this->descCompare('vs tahun lalu', $tahunDiff))
                ->icon('heroicon-o-chart-bar')
                ->color($tahunDiff >= 0 ? 'warning' : 'success'),
        ];
    }

    private function idr(int $value): string
    {
        return 'Rp ' . Number::format($value, 0, locale: 'id');
    }

    private function pctDiff(int $current, int $previous): float
    {
        if ($previous === 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    private function descCompare(string $label, float $pct): string
    {
        $sign = $pct > 0 ? '+' : '';
        return "{$label} ({$sign}" . Number::format($pct, 1) . '%)';
    }
}
