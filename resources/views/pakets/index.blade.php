@extends('layouts.app')

@section('title', 'Paket Pekerja')

@push('css')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="m-0">Daftar Paket Pekerja</h5>
            <div class="d-flex gap-3 flex-wrap">
                {{-- button export --}}
                <a href="/pakets/export" class="btn btn-success">
                    <i class="mdi mdi-download"></i> Export Excel
                </a>
                {{-- button modal add --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">
                    <i class="mdi mdi-plus"></i> Tambah Paket
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table id="tbl_list" class="datatables-basic table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- modal add --}}
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-add" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('pakets.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Paket Pekerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="nilai_formatted" class="form-label">Nilai</label>
                            <input type="text" class="form-control" id="nilai_formatted" oninput="formatRupiah(this)"
                                required>
                            <input type="hidden" id="nilai" name="nilai">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Paket Pekerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-nilai-formatted" class="form-label">Nilai</label>
                            <input type="text" class="form-control" id="edit-nilai-formatted"
                                oninput="formatRupiah(this)" required>
                            <input type="hidden" id="edit-nilai" name="nilai">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script>
        // Format Rupiah untuk input
        function formatRupiah(input) {
            const numberString = input.value.replace(/[^,\d]/g, "").toString();
            const split = numberString.split(",");
            const sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                const separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            input.value = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
            const numericValue = parseFloat(numberString.replace(/\./g, "").replace(",", "."));
            document.getElementById("nilai").value = numericValue || 0;
        }

        $(document).ready(function() {
            // Render DataTable
            $('#tbl_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url()->current() }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nilai',
                        name: 'nilai',
                        render: function(data) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                maximumFractionDigits: 0
                            }).format(data);
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modal-edit" data-id="${data}" data-name="${row.name}" data-nilai="${row.nilai}">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <form action="/master/pakets/${data}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            `;
                        }
                    }
                ]
            });

            // Modal Edit
            $('#modal-edit').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const name = button.data('name');
                const nilai = button.data('nilai');

                const modal = $(this);
                modal.find('.modal-body #edit-name').val(name);
                modal.find('.modal-body #edit-nilai-formatted').val(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(nilai));
                modal.find('.modal-body #edit-nilai').val(nilai);
                modal.find('#form-edit').attr('action', '/master/pakets/' + id);
            });
        });
    </script>
@endpush
