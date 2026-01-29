<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Draft Slips</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin: 0 0 6px 0;
        }

        .muted {
            color: #666;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Slip Gaji - Draft</h2>
    <div class="muted">Generated: {{ $generatedAt->format('d M Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;">#</th>
                <th>Karyawan</th>
                <th class="right">Total Salary</th>
                <th class="right">Total Deduction</th>
                <th class="right">Net Salary</th>
                <th class="center">Payment Method</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slips as $i => $slip)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $slip->karyawan->name ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($slip->total_salary ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($slip->total_deduction ?? 0, 0, ',', '.') }}</td>
                    <td class="right"><b>Rp {{ number_format($slip->total_net_salary ?? 0, 0, ',', '.') }}</b></td>
                    <td class="center">{{ strtoupper($slip->karyawan->method_payment ?? '-') }}
                        @if ($slip->karyawan->method_payment === 'transfer' || $slip->karyawan->method_payment === 'ewallet')
                            <br>
                            {{ $slip->karyawan->bank_name ?? '-' }} <br>
                            <small>
                                {{ $slip->karyawan->bank_account_number ?? '-' }} A/N
                                {{ $slip->karyawan->bank_account_name ?? '-' }}
                            </small>
                        @endif
                    </td>
                    <td class="center">{{ strtoupper($slip->status ?? '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada draft slip.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
