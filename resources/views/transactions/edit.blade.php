@extends('layouts.app')

@section('title', 'Edit Transaksi')

@push('css')
<link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
<link rel="stylesheet" href="/assets/vendor/libs/quill/typography.css" />
<link rel="stylesheet" href="/assets/vendor/libs/quill/katex.css" />
<link rel="stylesheet" href="/assets/vendor/libs/quill/editor.css" />
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
            <form action="{{ route('transactions.update',$transaction->id) }}" method="POST" class="card-body" id="transaction-form">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date">Tanggal</label>
                        <input type="date" class="form-control" value="{{ $transaction->transaction_date->format('Y-m-d') }}" id="date" name="transaction_date" required>
                        @error('transaction_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount">Jumlah</label>
                        <input type="number" value="{{$transaction->amount}}" class="form-control" id="amount" name="amount" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category">Kategori</label>
                        <select class="form-control" id="category" name="category_id" required>
                            <option value="" disabled selected>
                                Pilih kategori
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if ($transaction->category_id === $category->id)
                                    selected
                                @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type">Tipe</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="" disabled selected>Pilih tipe</option>
                            <option value="masuk" @if ($transaction->type ==="masuk")
                                selected
                            @endif>Pemasukan</option>
                            <option value="keluar" @if ($transaction->type ==="keluar")
                                selected
                            @endif>Pengeluaran</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description">Deskripsi</label>
                        <div class="col-12">
                            <div id="snow-toolbar">
                                <span class="ql-formats">
                                  <select class="ql-font"></select>
                                  <select class="ql-size"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-bold"></button>
                                  <button class="ql-italic"></button>
                                  <button class="ql-underline"></button>
                                  <button class="ql-strike"></button>
                                </span>
                                <span class="ql-formats">
                                  <select class="ql-color"></select>
                                  <select class="ql-background"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-script" value="sub"></button>
                                  <button class="ql-script" value="super"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-header" value="1"></button>
                                  <button class="ql-header" value="2"></button>
                                  <button class="ql-blockquote"></button>
                                  <button class="ql-code-block"></button>
                                </span>
                              </div>
                              <div id="snow-editor">
                              </div>
                              <input type="hidden" name="description" id="description">
                          </div>
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
