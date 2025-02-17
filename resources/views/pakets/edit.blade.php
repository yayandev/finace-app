@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3">Edit Paket</h4>

        <div class="card">
            <div class="card-body">
                <form action="/master/pakets/{{ $paket->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nama Paket<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('name', $paket->name) }}" id="name" name="name" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nilai_formatted" class="form-label">Nilai<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  id="nilai_formatted"  oninput="formatRupiah(this)" required>
                            <input type="hidden" id="nilai" name="nilai" value="{{ old('nilai', $paket->nilai) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="konsultan" class="form-label">Konsultan</label>
                            <select name="konsultan" id="konsultan" class="form-select">
                                <option value="" {{ old('konsultan', $paket->konsultan) == '' ? 'selected' : '' }}>Pilih konsultan</option>
                                <option value="Perencanaan" {{ old('konsultan', $paket->konsultan) == 'Perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                                <option value="Pengawasan" {{ old('konsultan', $paket->konsultan) == 'Pengawasan' ? 'selected' : '' }}>Pengawasan</option>
                                <option value="Kajian" {{ old('konsultan', $paket->konsultan) == 'Kajian' ? 'selected' : '' }}>Kajian</option>
                                <option value="SLF" {{ old('konsultan', $paket->konsultan) == 'SLF' ? 'selected' : '' }}>SLF</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="kontruksi" class="form-label">Kontruksi</label>
                            <select name="kontruksi" id="kontruksi" class="form-select">
                                <option value="" {{ old('kontruksi', $paket->kontruksi) == '' ? 'selected' : '' }}>Pilih Kontruksi</option>
                                <option value="Jalan" {{ old('kontruksi', $paket->kontruksi) == 'Jalan' ? 'selected' : '' }}>Jalan</option>
                                <option value="Jembatan" {{ old('kontruksi', $paket->kontruksi) == 'Jembatan' ? 'selected' : '' }}>Jembatan</option>
                                <option value="Gedung" {{ old('kontruksi', $paket->kontruksi) == 'Gedung' ? 'selected' : '' }}>Gedung</option>
                                <option value="Saluran" {{ old('kontruksi', $paket->kontruksi) == 'Saluran' ? 'selected' : '' }}>Saluran</option>
                                <option value="Pemeliharaan" {{ old('kontruksi', $paket->kontruksi) == 'Pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="pengadaan" class="form-label">Pengadaan</label>
                            <select name="pengadaan" id="pengadaan" class="form-select">
                                <option value="" {{ old('pengadaan', $paket->pengadaan) == '' ? 'selected' : '' }}>Pilih Pengadaan</option>
                                <option value="ATK" {{ old('pengadaan', $paket->pengadaan) == 'ATK' ? 'selected' : '' }}>ATK</option>
                                <option value="Mamin" {{ old('pengadaan', $paket->pengadaan) == 'Mamin' ? 'selected' : '' }}>Mamin</option>
                                <option value="Komputer" {{ old('pengadaan', $paket->pengadaan) == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                                <option value="Alat Listrik" {{ old('pengadaan', $paket->pengadaan) == 'Alat Listrik' ? 'selected' : '' }}>Alat Listrik</option>
                                <option value="Plastik Medis" {{ old('pengadaan', $paket->pengadaan) == 'Plastik Medis' ? 'selected' : '' }}>Plastik Medis</option>
                                <option value="Plastik Biasa" {{ old('pengadaan', $paket->pengadaan) == 'Plastik Biasa' ? 'selected' : '' }}>Plastik Biasa</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="penerima" class="form-label">Penerima</label>
                            <input type="text" class="form-control" id="penerima" name="penerima" value="{{ old('penerima', $paket->penerima) }}">
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
                                    <option value="{{ $i }}" {{ old('periode', $paket->periode) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="no_kontrak" class="form-label">No Kontrak</label>
                            <input type="text" class="form-control" id="no_kontrak" name="no_kontrak" value="{{ old('no_kontrak', $paket->no_kontrak) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="tanggal_kontrak" class="form-label">Tanggal Kontrak</label>
                            <input type="date" class="form-control" id="tanggal_kontrak" name="tanggal_kontrak" value="{{ old('tanggal_kontrak', $paket->tanggal_kontrak) }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="no_bastp" class="form-label">No BASTP</label>
                            <input type="text" class="form-control" id="no_bastp" name="no_bastp" value="{{ old('no_bastp', $paket->no_bastp) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="uraian" class="form-label">Uraian</label>
                            <textarea name="uraian" id="uraian" class="form-control" rows="3">{{ old('uraian', $paket->uraian) }}</textarea>
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
         let nilai_formatted = document.getElementById('nilai_formatted');
        const paket = @json($paket);
        if (paket.nilai) {
            nilai_formatted.value = paket.nilai.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            // hapus 2 nol diakhir
            nilai_formatted.value = nilai_formatted.value.slice(0, -2);
            //hapus titik diakhir
            nilai_formatted.value = nilai_formatted.value.replace(/\.$/, "");
        }
        });

    </script>
@endpush
