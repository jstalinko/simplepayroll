<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        .muted {
            color: #666;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .summary td {
            font-weight: bold;
        }

        .no-border td {
            border: none;
            padding: 2px 0;
        }
    </style>
</head>

<body>
    <div class="row">
        <div style="display:flex; gap:12px; align-items:center;">
            @if (!empty($logoPath) && file_exists($logoPath))
                @php
                    $LOGOENCRYPT = base64_encode(file_get_contents($logoPath));
                    $FILETYPE = mime_content_type($logoPath);
                @endphp
                <img src="data:{{ $FILETYPE }};base64,{{ $LOGOENCRYPT }}" style="height:55px; width:auto;">
            @endif

            <div>
                <div class="title">{{ $company['company_name'] ?? 'Company Name' }}</div>
                <div class="muted">{{ $company['company_address'] ?? '-' }}</div>
                <div class="muted">
                    {{ $company['company_phone'] ?? '-' }} | {{ $company['company_email'] ?? '-' }}
                </div>
            </div>
        </div>

        <div class="right">
            <div class="title">SLIP GAJI</div>
            <div class="muted">Status: {{ strtoupper($slip->status) }}</div>
            <div class="muted">
                Periode:
                {{ \Carbon\Carbon::parse($slip->period_start)->locale('id')->translatedFormat('j F Y') }}
                -
                {{ \Carbon\Carbon::parse($slip->period_end)->locale('id')->translatedFormat('j F Y') }}
            </div>
        </div>
    </div>


    <table class="no-border">
        <tr>
            <td><b>Karyawan</b></td>
            <td>: {{ $slip->karyawan->name ?? '-' }}</td>
            <td><b>Posisi</b></td>
            <td>: {{ $slip->karyawan->position ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Email</b></td>
            <td>: {{ $slip->karyawan->email ?? '-' }}</td>
            <td><b>Phone</b></td>
            <td>: {{ $slip->karyawan->phone ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Income</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Main Salary</td>
                <td class="right">Rp {{ number_format($slip->main_salary ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Overtime Pay</td>
                <td class="right">Rp {{ number_format($slip->overtime_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Meal Pay</td>
                <td class="right">Rp {{ number_format($slip->meal_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Transportation Pay</td>
                <td class="right">Rp {{ number_format($slip->transportation_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bonus @if ($slip->bonus_description)
                        <span class="muted">({{ $slip->bonus_description }})</span>
                    @endif
                </td>
                <td class="right">Rp {{ number_format($slip->bonus ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="summary">
                <td>Total Income</td>
                <td class="right">Rp {{ number_format($slip->total_salary ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Deduction</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Late Deduction</td>
                <td class="right">Rp {{ number_format($slip->late_deduction ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Absent Deduction</td>
                <td class="right">Rp {{ number_format($slip->absent_deduction ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Break Stuff Deduction</td>
                <td class="right">Rp {{ number_format($slip->break_stuff_deduction ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Other Deduction @if ($slip->other_deduction_description)
                        <span class="muted">({{ $slip->other_deduction_description }})</span>
                    @endif
                </td>
                <td class="right">Rp {{ number_format($slip->other_deduction ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="summary">
                <td>Total Deduction</td>
                <td class="right">Rp {{ number_format($slip->total_deduction ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr class="summary">
                <td><b>NET SALARY</b></td>
                <td class="right"><b>Rp {{ number_format($slip->total_net_salary ?? 0, 0, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
    @php
        $k = $slip->karyawan;
        $method = $k->method_payment ?? null; // transfer | cash | ewallet
    @endphp

    <div style="margin-top: 14px; border: 1px solid #ddd; padding: 10px;">
        <div style="font-weight: bold; margin-bottom: 6px;">Catatan Pembayaran</div>

        <div>
            Gaji dibayar dengan: <b>{{ strtoupper($method ?? '-') }}</b>
        </div>

        @if (in_array($method, ['transfer', 'ewallet'], true))
            <div style="margin-top: 6px;">
                <div>Nama Rekening: <b>{{ $k->bank_account_name ?? '-' }}</b></div>
                <div>No Rekening: <b>{{ $k->bank_account_number ?? '-' }}</b></div>
                <div>Bank: <b>{{ $k->bank_name ?? '-' }}</b></div>
            </div>
        @endif
    </div>

    <p class="muted" style="margin-top: 14px;">
        Dokumen ini dibuat otomatis oleh sistem.
    </p>
</body>

</html>
