@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Gamification Card -->
            <div class="col-md-12 col-lg-6">
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
                            <h6 class="mb-2">Rp. {{ number_format($income, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Total Masuk</p>
                            <div class="badge bg-label-secondary rounded-pill">Keseluruhan</div>
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
                            <h6 class="mb-2">Rp. {{ number_format($expense, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Total Keluar</p>
                            <div class="badge bg-label-secondary rounded-pill">Keseluruhan</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Pengeluaran -->

            <!-- Saldo -->
            <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="mdi mdi-wallet mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($balance, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Saldo</p>
                            <div class="badge bg-label-secondary rounded-pill">Masuk - Keluar</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Saldo -->

            <!-- Transaksi Terakhir -->
            <div class="col-12 col-xl-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Transaksi Terakhir</h5>
                    </div>
                    <div class="card-body">
                       <div class="table-responsive">
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
                                        <td>
                                            {{-- badge --}}
                                            <span
                                            @if ($transaction->type == 'masuk')
                                                class="badge bg-success"
                                                @else
                                                class="badge bg-danger"
                                            @endif
                                            >{{ $transaction->type}}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       </div>
                    </div>
                </div>
            </div>
            <!--/ Transaksi Terakhir -->

            <div class="col-12 col-xl-6">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-1">Pemasukan dan Pengeluaran</h5>
                            <select id="selectYear" class="form-select form-select-sm">
                                @foreach (range(date('Y') - 5, date('Y')) as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-label-success rounded-pill">
                                <i class="mdi mdi-arrow-up mdi-14px text-success"></i>
                                <span class="align-middle" id="total_income">
                                    +{{ 'Rp' . number_format(array_sum($dataPemasukanTahunIniPerbulan), 0, ',', '.') }}
                                </span>
                            </span>
                            <span class="badge bg-label-danger rounded-pill">
                                <i class="mdi mdi-arrow-down mdi-14px text-danger"></i>
                                <span class="align-middle" id="total_expense">
                                    -{{ 'Rp' . number_format(array_sum($dataPengeluaranTahunIniPerbulan), 0, ',', '.') }}
                                </span>
                            </span>
                        </div>
                    </div>

                  <div class="card-body pt-3">
                    <canvas id="financeChart" class="chartjs" data-height="500"></canvas>
                  </div>
                </div>
            </div>

             <!-- Uang Masuk Tahun Ini -->
             <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="mdi mdi-arrow-up-circle mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($totalUangMasukTahunIni, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Uang Masuk</p>
                            <div class="badge bg-label-secondary rounded-pill">Tahun ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Uang Masuk Tahun Ini -->
            <!-- Uang Masuk Bulan Ini -->
            <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="mdi mdi-arrow-up-circle mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($totalUangMasukBulanIni, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Uang Masuk</p>
                            <div class="badge bg-label-secondary rounded-pill">Bulan ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Uang Masuk Bulan Ini -->

             <!-- Uang Masuk Hari Ini -->
             <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="mdi mdi-arrow-up-circle mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($totalUangMasukHariIni, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Uang Masuk</p>
                            <div class="badge bg-label-secondary rounded-pill">Hari ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Uang Masuk Hari Ini -->


            <!-- Uang Keluar Tahun Ini -->
            <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-danger rounded">
                                    <i class="mdi mdi-arrow-down-circle mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($totalUangKeluarTahunIni, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Uang Keluar</p>
                            <div class="badge bg-label-secondary rounded-pill">Tahun ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Uang Keluar Tahun Ini -->
            <!-- Uang Keluar Bulan Ini -->
            <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-danger rounded">
                                    <i class="mdi mdi-arrow-down-circle mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($totalUangKeluarBulanIni, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Uang Keluar</p>
                            <div class="badge bg-label-secondary rounded-pill">Bulan ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Uang Keluar Bulan Ini -->

             <!-- Uang Keluar Hari Ini -->
             <div class="col-lg-2 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-danger rounded">
                                    <i class="mdi mdi-arrow-down-circle mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1 mt-lg-1 mt-xl-4">
                            <h6 class="mb-2">Rp. {{ number_format($totalUangKeluarHariIni, 0, ',', '.') }}</h6>
                            <p class="mb-lg-2 mb-xl-3">Uang Keluar</p>
                            <div class="badge bg-label-secondary rounded-pill">Hari ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Uang Keluar Hari Ini -->


        </div>
    </div>
@endsection

@push('scripts')
    <!-- Vendors JS -->
    <script src="/assets/vendor/libs/chartjs/chartjs.js"></script>

    <script>
        $(document).ready(function () {
    const ctx = document.getElementById('financeChart').getContext('2d');
    let financeChart;

    function fetchDataAndRenderChart(year) {
        $.ajax({
            url: '{{ route("finance.data") }}',
            method: 'GET',
            data: { year: year },
            success: function (response) {
                const { dataPemasukanTahunIniPerbulan, dataPengeluaranTahunIniPerbulan, totalPemasukan, totalPengeluaran } = response;

                // Update badges
                $('#total_income').text(
                    new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    }).format(totalPemasukan)
                );

                $('#total_expense').text(
                    new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    }).format(totalPengeluaran)
                );

                // Update chart
                if (financeChart) {
                    financeChart.destroy();
                }

                financeChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: dataPemasukanTahunIniPerbulan,
                                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                borderColor: 'rgba(40, 167, 69, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pengeluaran',
                                data: dataPengeluaranTahunIniPerbulan,
                                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                borderColor: 'rgba(220, 53, 69, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            error: function (xhr) {
                console.error('Error fetching data:', xhr.responseText);
            }
        });
    }

    // Initialize chart with current year
    fetchDataAndRenderChart($('#selectYear').val());

    // Change year and reload chart
    $('#selectYear').on('change', function () {
        fetchDataAndRenderChart($(this).val());
    });
});

    </script>
@endpush
