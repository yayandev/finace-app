@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Gamification Card -->
            <div class="col-md-12 col-lg-8">
                <div class="card h-100">
                    <div class="d-flex align-items-end row">
                        <div class="col-md-6 order-2 order-md-1">
                            <div class="card-body">
                                <h4 class="card-title pb-xl-2">Selamat Datang di Aplikasi Keuangan!</h4>
                                <p class="mb-0">Kelola pemasukan, pengeluaran, dan laporan keuangan kamu dengan mudah.</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                            <div class="card-body pb-0 px-0 px-md-4 ps-0">
                                <img src="/assets/img/illustrations/illustration-john-light.png" height="180"
                                    alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png"
                                    data-app-dark-img="illustrations/illustration-john-dark.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Gamification Card -->

            <!-- Pemasukan -->
            <div class="col-lg-2 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="mdi mdi-wallet-plus mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h5 class="mb-2">Rp. {{ number_format($income, 0, ',', '.') }}</h5>
                            <p class="mb-lg-2 mb-xl-3">Total Pemasukan</p>
                            <div class="badge bg-label-secondary rounded-pill">Bulan ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Pemasukan -->

            <!-- Pengeluaran -->
            <div class="col-lg-2 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-danger rounded">
                                    <i class="mdi mdi-wallet-outline mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h5 class="mb-2">Rp. {{ number_format($expense, 0, ',', '.') }}</h5>
                            <p class="mb-lg-2 mb-xl-3">Total Pengeluaran</p>
                            <div class="badge bg-label-secondary rounded-pill">Bulan ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Pengeluaran -->

            <!-- Saldo -->
            <div class="col-lg-2 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="mdi mdi-wallet mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h5 class="mb-2">Rp. {{ number_format($balance, 0, ',', '.') }}</h5>
                            <p class="mb-lg-2 mb-xl-3">Saldo</p>
                            <div class="badge bg-label-secondary rounded-pill">Bulan ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Saldo -->

            <!-- Transaksi Terakhir -->
            <div class="col-12 col-xl-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Transaksi Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latest_transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->category->name }}</td>
                                        <td>Rp. {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                        <td>{{ $transaction->type == 'masuk' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ Transaksi Terakhir -->
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets/js/dashboards-analytics.js"></script>
@endpush
