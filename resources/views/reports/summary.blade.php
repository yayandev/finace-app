@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h2 class="mb-4">Ringkasan Laporan</h2>

    <!-- Form filter untuk memilih periode -->
    <form action="{{ route('reports.summary') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary mt-4">Tampilkan</button>
            </div>
        </div>
    </form>

    <!-- Menampilkan Ringkasan -->
    <div class="row">
        <div class="col-md-4">
            <div class="card   mb-3">
                <div class="card-header">Pemasukan</div>
                <div class="card-body">
                    <h5 class="card-title">Rp. {{ number_format($income, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card  mb-3">
                <div class="card-header">Pengeluaran</div>
                <div class="card-body">
                    <h5 class="card-title">Rp. {{ number_format($expense, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card  mb-3">
                <div class="card-header">Saldo</div>
                <div class="card-body">
                    <h5 class="card-title">Rp. {{ number_format($balance, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h5>Ringkasan Pemasukan & Pengeluaran</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                    </tr>
                </thead>
                <tbody id="summary_table">
                    <tr>
                        <td>Hari Ini</td>
                        <td id="income_today"></td>
                        <td id="expense_today"></td>
                    </tr>
                    <tr>
                        <td>Bulan Ini</td>
                        <td id="income_month"></td>
                        <td id="expense_month"></td>
                    </tr>
                    <tr>
                        <td>Tahun Ini</td>
                        <td id="income_year"></td>
                        <td id="expense_year"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script>function loadSummary() {
        $.ajax({
            url: '{{ route("transactions.summary") }}',
            method: 'GET',
            success: function (data) {
                $('#income_today').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(data.income_today));

                $('#expense_today').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(data.expense_today));

                $('#income_month').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(data.income_month));

                $('#expense_month').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(data.expense_month));

                $('#income_year').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(data.income_year));

                $('#expense_year').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(data.expense_year));
            },
            error: function (xhr) {
                console.error('Error loading summary:', xhr.responseText);
            }
        });
    }

    // Panggil fungsi loadSummary saat dokumen siap
    $(document).ready(function () {
        loadSummary();
    });
    </script>
@endpush
