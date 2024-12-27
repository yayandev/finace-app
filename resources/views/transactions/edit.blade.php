@extends('layouts.app')

@section('title', 'Edit Transaksi')

@push('css')
    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/quill/typography.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="m-0">Daftar Transaksi</h5>
            {{-- button modal add --}}
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left    "></i> Kembali
            </a>
        </div>

        <div class="card">
            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="card-body">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="transaction_date"
                            value="{{ $transaction->transaction_date->format('Y-m-d') }}" required>
                        @error('transaction_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount-display">Jumlah</label>
                        <input type="text" class="form-control" id="amount-display" value="{{ $transaction->amount }}"
                            required>
                        <input type="hidden" id="amount" name="amount" value="{{ $transaction->amount }}">
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category">Kategori</label>
                        <select class="form-control select2" id="category" name="category_id" required>
                            <option value="" disabled>Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $transaction->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type">Tipe</label>
                        <select class="select2 form-control" id="type" name="type" required>
                            <option value="" disabled>Pilih tipe</option>
                            <option value="masuk" {{ $transaction->type == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="keluar" {{ $transaction->type == 'keluar' ? 'selected' : '' }}>Pengeluaran
                            </option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="paket">Paket</label>
                        <select class="select2 form-control" id="paket" name="paket_id" required>
                            <option value="" disabled>Pilih Paket</option>
                            @foreach ($pakets as $paket)
                                <option value="{{ $paket->id }}"
                                    {{ $transaction->paket_id == $paket->id ? 'selected' : '' }}>{{ $paket->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('paket_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="3">{{ $transaction->description }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/vendor/libs/quill/katex.js"></script>
    <script src="/assets/vendor/libs/quill/quill.js"></script>
    <script src="/assets/js/forms-editors.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

    <script src="/assets/vendor/libs/select2/select2.js"></script>
    <script src="/assets/vendor/libs/tagify/tagify.js"></script>
    <script src="/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets/vendor/libs/bloodhound/bloodhound.js"></script>

    <script src="/assets/js/forms-selects.js"></script>
    <script src="/assets/js/forms-tagify.js"></script>
    <script src="/assets/js/forms-typeahead.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Cleave.js
            const cleaveInstance = new Cleave('#amount-display', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                prefix: 'Rp ',
                noImmediatePrefix: false,
                rawValueTrimPrefix: true,
                stripLeadingZeroes: true
            });

            //event ketika input amount display change
            document.getElementById('amount-display').addEventListener('input', function(e) {
                //set value amount dengan value dari cleave
                document.getElementById('amount').value = cleaveInstance.getRawValue();
            });
        });
    </script>
    <script>
        var quill = new Quill('#snow-editor', {
            theme: 'snow',
            modules: {
                toolbar: '#snow-toolbar'
            }
        });

        // set value to quill
        quill.root.innerHTML = @json($transaction->description);

        var form = document.getElementById('transaction-form');
        form.onsubmit = function() {
            var description = document.querySelector('input[name=description]');
            description.value = quill.root.innerHTML;
        };
    </script>
@endpush
