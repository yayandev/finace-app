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

                <div class="col-md-4 d-flex align-items-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary mt-4">Tampilkan</button>
                    @if ($transaksiMasuk)
                        <a href="/ringkasan-transaksi-paket?paket_id={{ $paketSelected->id }}"
                            class="btn btn-outline-primary mt-4">
                            Export Excel
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Menampilkan Ringkasan Masuk -->
        @if ($transaksiMasuk)
            <div class="card mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">
                                    Ringkasan Laporan Masuk {{ $paket->name }}
                                </th>
                            </tr>
                            <tr>
                                <th>Uraian Kegiatan</th>
                                <th class="text-end">Keluar</th>
                                <th class="text-end">Saldo</th>
                            </tr>

                        </thead>
                        <tbody>
                            @php
                                $saldo = $paketSelected->nilai;
                            @endphp
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                            @foreach ($transaksiMasuk as $item)
                                @php
                                    $saldo -= $item->amount;
                                @endphp
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-end">{{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


        <!-- Menampilkan Ringkasan Keluar -->
        @if ($transaksiKeluar)
            <div class="card mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">
                                    Ringkasan Laporan Keluar {{ $paket->name }}
                                </th>
                            </tr>
                            <tr>
                                <th>Uraian Kegiatan</th>
                                <th class="text-end">Keluar</th>
                                <th class="text-end">Saldo</th>
                            </tr>

                        </thead>
                        <tbody>
                            @php
                                $saldo = $paketSelected->nilai;
                            @endphp
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                            @foreach ($transaksiKeluar as $item)
                                @php
                                    $saldo -= $item->amount;
                                @endphp
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-end">{{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
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
