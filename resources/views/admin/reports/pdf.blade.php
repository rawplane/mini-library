<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Buku - Perpustakaan Mini</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1e1b4b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #64748b;
        }

        .meta-info {
            margin-bottom: 15px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
            font-size: 11px;
        }

        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-grid td {
            text-align: center;
            padding: 5px;
        }

        .summary-title {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
        }

        .summary-val {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 2px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 10px;
        }

        table.data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-dikembalikan {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-dipinjam {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-terlambat {
            background-color: #ffe4e6;
            color: #9f1239;
        }

        .footer-sign {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }

        .sign-col {
            width: 250px;
            float: right;
            text-align: center;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Perpustakaan Mini</h1>
        <p>Laporan Resmi Rekapitulasi Sirkulasi & Peminjaman Buku</p>
    </div>

    <div class="meta-info">
        <table class="meta-table">
            <tr>
                <td style="width: 120px;"><strong>Periode Laporan</strong></td>
                <td>: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</td>
                <td style="text-align: right; width: 140px;"><strong>Tanggal Cetak</strong></td>
                <td style="text-align: right; width: 120px;">: {{ now()->translatedFormat('d F Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Filter Status</strong></td>
                <td>: {{ $status && $status !== 'semua' ? ucfirst($status) : 'Semua Status' }}</td>
                <td style="text-align: right;"><strong>Petugas</strong></td>
                <td style="text-align: right;">: {{ auth()->user()->name ?? 'Administrator' }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-title">Total Transaksi</div>
                    <div class="summary-val">{{ number_format($totalLoans) }}</div>
                </td>
                <td>
                    <div class="summary-title">Total Buku Dipinjam</div>
                    <div class="summary-val">{{ number_format($totalBooksBorrowed) }}</div>
                </td>
                <td>
                    <div class="summary-title">Total Denda</div>
                    <div class="summary-val" style="color: #4f46e5;">Rp {{ number_format($totalFines, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 120px;">Nama Peminjam</th>
                <th>Judul Buku</th>
                <th style="width: 70px; text-align: center;">Tgl Pinjam</th>
                <th style="width: 70px; text-align: center;">Jatuh Tempo</th>
                <th style="width: 70px; text-align: center;">Tgl Kembali</th>
                <th style="width: 75px; text-align: center;">Status</th>
                <th style="width: 75px; text-align: right;">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $loan->user->name ?? '-' }}</strong><br>
                        <span style="color: #64748b; font-size: 9px;">{{ $loan->user->email ?? '' }}</span>
                    </td>
                    <td>
                        @foreach($loan->books as $b)
                            &bull; {{ $b->judul }}<br>
                        @endforeach
                    </td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span>
                    </td>
                    <td style="text-align: right; font-weight: bold;">
                        Rp {{ number_format($loan->denda, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; color: #94a3b8;">
                        Tidak ada transaksi peminjaman pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Block -->
    <div class="footer-sign">
        <div class="sign-col">
            <p style="margin: 0 0 50px 0;">
                Mengetahui,<br>
                Kepala Perpustakaan
            </p>
            <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                {{ auth()->user()->name ?? 'Administrator' }}
            </p>
            <p style="margin: 2px 0 0 0; color: #64748b; font-size: 10px;">
                NIP. 19850101 201001 1 001
            </p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
