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
                <a href="/master/pakets/create" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah Paket
                </a>
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
                                <a href="/master/pakets/${data}/edit" class="btn btn-sm btn-warning" >
                                    <i class="mdi mdi-pencil"></i>
                                </a>
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
        });
    </script>
@endpush
