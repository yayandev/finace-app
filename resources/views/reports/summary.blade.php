@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2 class="mb-4">Ringkasan Laporan</h2>

        <!-- Form filter untuk memilih periode -->
        <form action="{{ route('reports.summary') }}" method="GET" class="mb-4">
            <div class="row d-flex align-items-center">
                <div class="col-md-4">
                    <label for="paket_id" class="form-label">Paket Pekerja</label>
                    <select name="paket_id" id="paket_id" class="select2 form-control">
                        <option value="" disabled selected>
                            Pilih Paket Pekerja
                        </option>
                        @foreach ($pakets as $paket)
                            <option value="{{ $paket->id }}" @if (request('paket_id') == $paket->id) selected @endif>
                                {{ $paket->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8 d-flex align-items-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary mt-4">Tampilkan</button>
                    @if ($transactions)
                        <a href="/ringkasan-transaksi-paket?paket_id={{ $paketSelected->id }}"
                            class="btn btn-outline-primary mt-4">
                            Export Excel
                        </a>
                        <a href="/pdf-ringkasan-transaksi-paket?paket_id={{ $paketSelected->id }}"
                            class="btn btn-outline-primary mt-4">
                            Export PDF
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Menampilkan Ringkasan Masuk -->
        @if ($transactions)
            <div class="card mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th colspan="1" class="text-start">
                                    Ringkasan Laporan {{ $paketSelected->name }}
                                </th>
                                <th class="text-start" colspan="2">
                                    {{ number_format($paketSelected->nilai, 0, ',', '.') }}
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
                                <td colspan="2" class="text-end">Total</td>
                                <td class="text-end">
                                    {{ number_format($transactions->where('type', 'masuk')->sum('amount'), 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($transactions->where('type', 'keluar')->sum('amount'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($transactions)
            <div class="card mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Sisa Saldo Tagihan</th>
                                <th class="text-end">
                                    {{ number_format($paketSelected->nilai - $transactions->where('type', 'masuk')->sum('amount')) }}
                                </th>
                            </tr>
                            <tr>
                                <th>Sisa Saldo Paket Pekarjaan</th>
                                <th class="text-end">
                                    {{ number_format($paketSelected->nilai - $transactions->where('type', 'keluar')->sum('amount')) }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
@endpush
@push('scripts')
    <script src="/assets/vendor/libs/select2/select2.js"></script>
    <script src="/assets/vendor/libs/tagify/tagify.js"></script>
    <script src="/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets/vendor/libs/bloodhound/bloodhound.js"></script>

    <script src="/assets/js/forms-selects.js"></script>
    <script src="/assets/js/forms-tagify.js"></script>
    <script src="/assets/js/forms-typeahead.js"></script>
@endpush
