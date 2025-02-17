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

    {{-- modal detail --}}
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
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail" data-id="${data}" data-name="${row.name}" data-nilai="${row.nilai}" data-konsultan="${row.konsultan}" data-periode="${row.periode}" data-kontruksi="${row.kontruksi}" data-pengadaan="${row.pengadaan}" data-tanggal_kontrak="${row.tanggal_kontrak}" data-no_bastp="${row.no_bastp}" data-uraian="${row.uraian}">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                            `;
                        }
                    }
                ]
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
        });
    </script>
@endpush

