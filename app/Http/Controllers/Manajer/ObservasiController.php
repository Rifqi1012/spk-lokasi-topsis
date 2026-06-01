<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreObservasiRequest;
use App\Models\ObservasiLokasi;
use App\Models\Lokasi;
use App\Services\ObservasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObservasiController extends Controller
{
    public function __construct(
        private ObservasiService $observasiService
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search');

        $observasis = ObservasiLokasi::query()
            ->with(['lokasi', 'user'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('lokasi', function($q) use ($search) {
                    $q->where('nama_lokasi', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manajer.observasi.index', compact('observasis', 'search'));
    }

    public function create()
    {
        // Get list of Lokasi that haven't been observed yet (optional: or all lokasi)
        // We'll get all locations just in case they want multiple observations for the same location
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        return view('manajer.observasi.create', compact('lokasis'));
    }

    public function store(StoreObservasiRequest $request)
    {
        $data = $request->validated();
        $photos = $request->file('photos', []);

        $this->observasiService->storeObservation(
            $data, 
            $photos, 
            Auth::id()
        );

        return redirect()->route('manajer.observasi.index')
            ->with('success', 'Observasi dan Penilaian berhasil disimpan.');
    }

    public function show(ObservasiLokasi $observasi)
    {
        $observasi->load(['lokasi', 'user', 'dokumentasiLokasis', 'penilaians.detailPenilaians']);
        return view('manajer.observasi.show', compact('observasi'));
    }

    public function destroy(ObservasiLokasi $observasi)
    {
        // We might need to delete the images from storage if required, 
        // but due to SoftDeletes on ObservasiLokasi, we just soft delete it.
        $observasi->delete();
        return redirect()->route('manajer.observasi.index')->with('success', 'Observasi berhasil dihapus.');
    }
}
