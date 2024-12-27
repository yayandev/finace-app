@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@push('css')
<link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
<link rel="stylesheet" href="/assets/vendor/libs/flatpickr/flatpickr.css" />
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
                <form action="{{ route('transactions.import') }}" method="POST" enctype="multipart/form-data" class="d-inline">
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
                <div class="col-md-4">
                    <label for="date_start" class="form-label">Tanggal Mulai</label>
                    <input type="text" id="date_start" class="form-control" placeholder="Pilih tanggal mulai">
                </div>
                <div class="col-md-4">
                    <label for="date_end" class="form-label">Tanggal Akhir</label>
                    <input type="text" id="date_end" class="form-control" placeholder="Pilih tanggal akhir">
                </div>
                <div class="col-md-4">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" name="type" class="form-control">
                        <option value="">Semua</option>
                        <option value="masuk">Masuk</option>
                        <option value="keluar" >Keluar</option>
                    </select>
                </div>
            </div>
            <button id="filter_btn" class="btn btn-primary">Terapkan Filter</button>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table id="tbl_list" class="datatables-basic table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right">Total:</th>
                        <th id="total_amount"></th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
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
                    <label class="form-label">Category</label>
                    <input type="text" id="category" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"
                        >Amount</label
                    >
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



@endsection

@push('scripts')
<script src="/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="/assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script>
$(document).ready(function () {
    // Initialize date pickers
    flatpickr('#date_start', { dateFormat: 'Y-m-d' });
    flatpickr('#date_end', { dateFormat: 'Y-m-d' });

    let table = $('#tbl_list').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url()->current() }}',
            data: function (d) {
                d.date_start = $('#date_start').val();
                d.date_end = $('#date_end').val();
                d.type = $('#type').val(); // Add type filter
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            {
                data: 'type',
                name: 'type',
                render: function (data, type, row) {
                    if (data === 'masuk') {
                        return '<span class="badge bg-success">Masuk</span>';
                    } else if (data === 'keluar') {
                        return '<span class="badge bg-danger">Keluar</span>';
                    }
                    return data;
                }
            },
            { data: 'category.name', name: 'category.name' },
            { data: 'amount', name: 'amount',
                render: function (data, type, row) {
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
                render: function (data, type, row) {
                    const date = new Date(data);
                    return date.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-detail" data-id="${data}" data-type="${row.type}" data-category="${row.category.name}" data-amount="${row.amount}" data-date="${row.transaction_date}" data-description="${row.description}" data-user="${row.user.name}">
                            <i class="mdi mdi-eye"></i>
                            </button>
                        <a class="btn btn-sm btn-warning" href="/transactions/${data}/edit">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <form action="/transactions/${data}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            @can('delete-transactions')
                            <form action="/transactions/${data}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                            @endcan
                        </form>
                    `;
                }
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // Calculate the total for column Amount
            var total = api
                .column(3)
                .data()
                .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

            // Update the footer
            $(api.column(3).footer()).html(
                new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(total)
            );
        }
    });

    // Filter button event
    $('#filter_btn').on('click', function () {
        table.ajax.reload();

        let btnExport = $('#btn-export');
        let url = '{{ route('transactions.export') }}';
        let dateStart = $('#date_start').val();
        let dateEnd = $('#date_end').val();
        let type = $('#type').val();
        
        // Update the export button URL
        btnExport.attr('href', `${url}?date_start=${dateStart}&date_end=${dateEnd}&type=${type}`);
    });
});

// Show detail modal
$('#modal-detail').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);

    modal.find('#user').val(button.data('user'));
    modal.find('#type').val(button.data('type'));
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
</script>
@endpush
