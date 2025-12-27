<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - {{ $date->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1a202c;
            line-height: 1.5;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: #2d3748;
        }

        .header p {
            color: #718096;
            margin-top: 0.5rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }

        .card-label {
            font-size: 0.875rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .card-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0.5rem;
            color: #2d3748;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background-color: #f7fafc;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        .print-controls {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media print {
            .print-controls {
                display: none;
            }

            body {
                padding: 0;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #4c51bf;
            color: white;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-select {
            padding: 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e0;
        }
    </style>
</head>

<body>

    <div class="print-controls">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">&larr; Kembali ke Dashboard</a>

        <div class="filter-form">
            <form action="{{ route('admin.reports.revenue') }}" method="GET">
                <input type="month" name="month" value="{{ $month }}" class="form-select" onchange="this.form.submit()">
            </form>
            <button onclick="window.print()" class="btn btn-primary">
                Cetak PDF / Print
            </button>
        </div>
    </div>

    <div class="header">
        <h1>Laporan Pendapatan Bulanan</h1>
        <p>{{ $date->translatedFormat('F Y') }}</p>
        <p style="font-size: 0.875rem; margin-top: 0.25rem;">Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="summary-grid">
        <div class="card">
            <div class="card-label">Total Transaksi</div>
            <div class="card-value">{{ number_format($totalTransactions) }}</div>
        </div>
        <div class="card">
            <div class="card-label">Total Pendapatan</div>
            <div class="card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Jumlah Transaksi</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailyRevenue as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                    <td class="text-right">{{ number_format($item->count) }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #a0aec0; padding: 2rem;">Belum ada data transaksi
                        untuk bulan ini.</td>
                </tr>
            @endforelse
            @if($dailyRevenue->isNotEmpty())
                <tr style="background-color: #f7fafc; font-weight: bold;">
                    <td>Total</td>
                    <td class="text-right">{{ number_format($totalTransactions) }}</td>
                    <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <script>
        // Auto print prompt if query param present maybe? or just leave it manual
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>