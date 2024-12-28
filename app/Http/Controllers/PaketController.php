<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaketController extends Controller
{
    // Menampilkan daftar paket
    public function index()
    {
        if (request()->ajax()) {
            $pakets = Paket::query();
            return DataTables::of($pakets)
                ->make();
        }
        return view('pakets.index');
    }

    // Menampilkan form untuk membuat paket baru
    public function create()
    {
        return view('pakets.create');
    }

    // Menyimpan data paket baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        Paket::create($request->all());
        return redirect()->route('pakets.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    // Menampilkan detail satu paket
    public function show($id)
    {
        $paket = Paket::findOrFail($id);
        return view('pakets.show', compact('paket'));
    }

    // Menampilkan form untuk mengedit paket
    public function edit($id)
    {
        $paket = Paket::findOrFail($id);
        return view('pakets.edit', compact('paket'));
    }

    // Mengupdate data paket
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        $paket = Paket::findOrFail($id);
        $paket->update($request->all());
        return redirect()->route('pakets.index')->with('success', 'Paket berhasil diperbarui.');
    }

    // Menghapus paket
    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();
        return redirect()->route('pakets.index')->with('success', 'Paket berhasil dihapus.');
    }
}
