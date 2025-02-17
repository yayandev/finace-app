@extends('layouts.app')

@section('title', 'Tambah Paket')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3">Tambah Paket</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pakets.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nama Paket<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nilai_formatted" class="form-label">Nilai<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nilai_formatted" oninput="formatRupiah(this)"
                                required>
                            <input type="hidden" id="nilai" name="nilai">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="konsultan" class="form-label">konsultan</label>
                            <select name="konsultan" id="konsultan" class="form-select">
                                <option value="" selected>
                                    Pilih konsultan
                                </option>
                                <option value="Perencanaan">Perencanaan</option>
                                <option value="Pengawasan">Pengawasan</option>
                                <option value="Kajian">Kajian</option>
                                <option value="SLF">SLF</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="kontruksi" class="form-label">Kontruksi</label>
                            <select name="kontruksi" id="kontruksi" class="form-select">
                                <option value="" selected>
                                    Pilih Kontruksi
                                </option>
                                <option value="Jalan">Jalan</option>
                                <option value="Jembatan">Jembatan</option>
                                <option value="Gedung">Gedung</option>
                                <option value="Saluran">Saluran</option>
                                <option value="Pemeliharaan">Pemeliharaan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="pengadaan" class="form-label">Pengadaan</label>
                            <select name="pengadaan" id="pengadaan" class="form-select">
                                <option value="" selected>
                                    Pilih Pengadaan
                                </option>
                                <option value="ATK">ATK</option>
                                <option value="Mamin">Mamin</option>
                                <option value="Komputer">Komputer</option>
                                <option value="Alat Listrik">Alat Listrik</option>
                                <option value="Plastik Medis">Plastik Medis</option>
                                <option value="Plastik Biasa">Plastik Biasa</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="penerima" class="form-label">Penerima</label>
                            <input type="text" class="form-control" id="penerima" name="penerima" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="periode" class="form-label">Periode</label>
                            <select name="periode" id="periode" class="form-select">
                                <option value="" selected>
                                    Pilih Periode
                                </option>
                                @for ($i = date('Y'); $i <= date('Y') + 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="no_kontrak" class="form-label">
                                No Kontrak
                            </label>
                            <input type="text" class="form-control" id="no_kontrak" name="no_kontrak" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="tanggal_kontrak" class="form-label">Tanggal Kontrak</label>
                            <input type="date" class="form-control" id="tanggal_kontrak" name="tanggal_kontrak"
                                >
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="no_bastp" class="form-label">No BASTP </label>
                            <input type="text" class="form-control" id="no_bastp" name="no_bastp" >
                        </div>
                    </div>
                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label for="uraian" class="form-label">Uraian</label>
                            <textarea name="uraian" id="uraian" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pakets.index') }}" class="btn btn-secondary">Batal</a>
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

        });
    </script>
@endpush
