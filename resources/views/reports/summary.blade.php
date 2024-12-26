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
</div>
@endsection
