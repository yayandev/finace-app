@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@push('css')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/flatpickr/flatpickr.css" />

    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-3">
            <h5 class="m-0">Daftar Transaksi</h5>
            <div class="d-flex gap-3 flex-wrap mt-2">
                <!-- Tombol Download Template -->
                <div class="">
                    <a href="{{ route('transactions.downloadTemplate') }}" class="btn btn-info">
                        <i class="mdi mdi-download"></i> Download Template
                    </a>
                </div>

                <!-- Tombol Export Excel -->
                <div class="">
                    <a href="{{ route('transactions.export') }}" id="btn-export" class="btn btn-success">
                        <i class="mdi mdi-file-excel"></i> Export Excel
                    </a>
                </div>
                <div class="">
                    <!-- Form Import Excel -->
                    <form action="{{ route('transactions.import') }}" method="POST" enctype="multipart/form-data"
                        class="d-inline">
                        @csrf
                        <input type="file" name="file" class="form-control d-inline w-auto" required>
                        <button class="btn btn-primary">
                            <i class="mdi mdi-upload"></i> Import Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 mb-1">
                        <label for="date_start" class="form-label">Tanggal Mulai</label>
                        <input type="text" id="date_start" class="form-control" placeholder="Pilih tanggal mulai">
                    </div>
                    <div class="col-md-4 mb-1">
                        <label for="date_end" class="form-label">Tanggal Akhir</label>
                        <input type="text" id="date_end" class="form-control" placeholder="Pilih tanggal akhir">
                    </div>
                    <div class="col-md-4 mb-1">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="select2 form-control">
                            <option value="">Semua</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-1">
                        <label for="type" class="form-label">Category</label>
                        <select id="category_id" name="category_id" class="select2 form-control">
                            <option value="">Semua</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-1">
                        <label for="type" class="form-label">Paket</label>
                        <select id="paket_id" name="paket_id" class="select2 form-control">
                            <option value="">Semua</option>
                            @foreach ($pakets as $paket)
                                <option value="{{ $paket->id }}">{{ $paket->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mt-1 d-flex align-items-center justify-content-end gap-3 flex-wrap">
                        <button id="filter_btn" class="btn btn-sm inline-block btn-primary">Terapkan Filter</button>
                        <a href="" class="inline-block btn btn-sm btn-danger">Hapus Filter</a>
                    </div>
                </div>

            </div>
            <div class="card-datatable table-responsive pt-0">
                <table id="tbl_list" class="datatables-basic table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <input type="text" id="user" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <input type="text" id="type" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Paket</label>
                        <input type="text" id="paket" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" id="category" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" id="amount" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="text" id="date" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="description" class="form-control" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- modal detail paket --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name_detail" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name_detail" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nilai_detail" class="form-label">Nilai</label>
                            <input type="text" class="form-control" id="nilai_detail" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="konsultan_detail" class="form-label">Konsultan</label>
                            <input type="text" class="form-control" id="konsultan_detail" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="periode_detail" class="form-label">Periode</label>
                            <input type="text" class="form-control" id="periode_detail" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kontruksi_detail" class="form-label">Kontruksi</label>
                            <input type="text" class="form-control" id="kontruksi_detail" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pengadaan_detail" class="form-label">Pengadaan</label>
                            <input type="text" class="form-control" id="pengadaan_detail" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_kontrak_detail" class="form-label">Tanggal Kontrak</label>
                            <input type="text" class="form-control" id="tanggal_kontrak_detail" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="no_bastp_detail" class="form-label">No BASTP</label>
                            <input type="text" class="form-control" id="no_bastp_detail" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="uraian_detail" class="form-label">Uraian</label>
                            <textarea class="form-control" id="uraian_detail" rows="3" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/assets/vendor/libs/select2/select2.js"></script>
    <script src="/assets/vendor/libs/tagify/tagify.js"></script>
    <script src="/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets/vendor/libs/bloodhound/bloodhound.js"></script>

    <script src="/assets/js/forms-selects.js"></script>
    <script src="/assets/js/forms-tagify.js"></script>
    <script src="/assets/js/forms-typeahead.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script src="/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize date pickers
            flatpickr('#date_start', {
                dateFormat: 'Y-m-d'
            });
            flatpickr('#date_end', {
                dateFormat: 'Y-m-d'
            });

            let table = $('#tbl_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url()->current() }}',
                    data: function(d) {
                        d.date_start = $('#date_start').val();
                        d.date_end = $('#date_end').val();
                        d.type = $('#type').val(); // Add type filter
                        d.category_id = $('#category_id').val(); // Add category_id filter
                        d.paket_id = $('#paket_id').val(); // Add paket_id filter
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data, type, row) {
                            if (data === 'masuk') {
                                return '<span class="badge bg-success">Masuk</span>';
                            } else if (data === 'keluar') {
                                return '<span class="badge bg-danger">Keluar</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'category.name',
                        name: 'category.name'
                    },
                    {
                        data: 'paket',
                        render: function(data, type, row) {
                            return `
                            <button data-bs-toggle="modal" data-bs-target="#modalDetail" class="btn btn-sm btn-warning" data-id="${data.id}" data-name="${data.name}" data-nilai="${data.nilai}" data-konsultan="${data.konsultan}" data-periode="${data.periode}" data-kontruksi="${data.kontruksi}" data-pengadaan="${data.pengadaan}" data-tanggal_kontrak="${data.tanggal_kontrak}" data-no_bastp="${data.no_bastp}" data-uraian="${data.uraian}">
                                ${data.name}
                            </button>
                            `;
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, row) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                maximumFractionDigits: 0
                            }).format(data);
                        }
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date',
                        render: function(data, type, row) {
                            const date = new Date(data);
                            return date.toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-target="#modal-detail" data-id="${data}" data-type="${row.type}" data-category="${row.category.name}" data-amount="${row.amount}" data-date="${row.transaction_date}" data-description="${row.description}" data-user="${row.user.name}" data-paket="${row.paket.name}">
                            <i class="mdi mdi-eye"></i>
                            </button>
                        <a class="btn btn-xs btn-warning" href="/transactions/${data}/edit">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <form action="/transactions/${data}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            @can('delete-transactions')
                            <form action="/transactions/${data}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                            @endcan
                        </form>
                    `;
                        }
                    }
                ],
            });

            // Filter button event
            $('#filter_btn').on('click', function() {
                table.ajax.reload();

                let btnExport = $('#btn-export');
                let url = '{{ route('transactions.export') }}';
                let dateStart = $('#date_start').val();
                let dateEnd = $('#date_end').val();
                let type = $('#type').val();
                let category_id = $('#category_id').val();
                let paket_id = $('#paket_id').val();

                // Update the export button URL
                btnExport.attr('href',
                    `${url}?date_start=${dateStart}&date_end=${dateEnd}&type=${type}&category_id=${category_id}&paket_id=${paket_id}`
                );
            });
        });

        // Show detail modal
        $('#modal-detail').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#user').val(button.data('user'));
            modal.find('#type').val(button.data('type'));
            modal.find('#paket').val(button.data('paket'));
            modal.find('#category').val(button.data('category'));
            modal.find('#amount').val(new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(button.data('amount')));
            modal.find('#date').val(new Date(button.data('date')).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));
            modal.find('#description').val(button.data('description'));
        });

         // Detail modal
         $('#modalDetail').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const name = button.data('name');
                const nilai = button.data('nilai');
                const konsultan = button.data('konsultan');
                const periode = button.data('periode');
                const kontruksi = button.data('kontruksi');
                const pengadaan = button.data('pengadaan');
                const tanggal_kontrak = button.data('tanggal_kontrak');
                const no_bastp = button.data('no_bastp');
                const uraian = button.data('uraian');

                const modal = $(this);
                const nilaiFormatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(nilai);
                modal.find(`#name_detail`).val(name);
                modal.find(`#nilai_detail`).val(
                    nilaiFormatted
                );
                modal.find(`#konsultan_detail`).val(konsultan);
                modal.find(`#periode_detail`).val(periode);
                modal.find(`#kontruksi_detail`).val(kontruksi);
                modal.find(`#pengadaan_detail`).val(pengadaan);
                modal.find(`#tanggal_kontrak_detail`).val(tanggal_kontrak);
                modal.find(`#no_bastp_detail`).val(no_bastp);
                modal.find(`#uraian_detail`).val(uraian);
            });
    </script>
@endpush
