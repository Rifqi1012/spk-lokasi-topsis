<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasis = Lokasi::query()
            ->when($search, function ($query, $search) {
                return $query->where('nama_lokasi', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%");
            })
            ->with('creator') // Eager load user creator
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manajer.lokasi.index', compact('lokasis', 'search'));
    }

    public function create()
    {
        return view('manajer.lokasi.create');
    }

    public function store(StoreLokasiRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id(); // Assign the currently logged-in user

        Lokasi::create($data);

        return redirect()->route('manajer.lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function show(Lokasi $lokasi)
    {
        return view('manajer.lokasi.show', compact('lokasi'));
    }

    public function edit(Lokasi $lokasi)
    {
        return view('manajer.lokasi.edit', compact('lokasi'));
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        $lokasi->update($request->validated());
        return redirect()->route('manajer.lokasi.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();
        return redirect()->route('manajer.lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
