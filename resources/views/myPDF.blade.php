<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ringkasan Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }

        th {
            background-color: #f8f9fa;
        }

        .text-end {
            text-align: right;
        }

        .mb-5 {
            margin-bottom: 20px;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card mb-5">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">
                            Ringkasan Laporan {{ $paket->name }}
                        </th>
                        <th class="text-start" colspan="2">
                            {{ number_format($paket->nilai, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th class="text-start">Tanggal</th>
                        <th>Uraian Kegiatan</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $item)
                        <tr>
                            <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="text-end">
                                @if ($item->type == 'masuk')
                                    {{ number_format($item->amount, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($item->type == 'keluar')
                                    {{ number_format($item->amount, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2" class="text-start">Total</th>
                        <td class="text-end">
                            {{ number_format($transactions->where('type', 'masuk')->sum('amount'), 0, ',', '.') }}
                        </td>
                        <td class="text-end">
                            {{ number_format($transactions->where('type', 'keluar')->sum('amount'), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-start" colspan="2">Sisa Saldo Tagihan</th>
                        <td class="text-end" colspan="2">
                            {{ number_format($paket->nilai - $transactions->where('type', 'masuk')->sum('amount')) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-start" colspan="2">Sisa Saldo Paket Pekarjaan</th>
                        <td class="text-end" colspan="2">
                            {{ number_format($paket->nilai - $transactions->where('type', 'keluar')->sum('amount')) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
