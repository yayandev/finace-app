@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="m-0">Daftar Transaksi</h5>
            {{-- button modal add --}}
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Transaksi
            </a>
        </div>

        <div class="card">
            <form action="{{ route('transactions.store') }}" method="POST" class="card-body">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="transaction_date" required>
                        @error('transaction_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount">Jumlah</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
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
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <option value="masuk">Pemasukan</option>
                            <option value="keluar">Pengeluaran</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
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
