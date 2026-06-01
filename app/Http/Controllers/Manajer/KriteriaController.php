<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKriteriaRequest;
use App\Http\Requests\UpdateKriteriaRequest;
use App\Models\Kriteria;
use App\Services\KriteriaService;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    protected $kriteriaService;

    public function __construct(KriteriaService $kriteriaService)
    {
        $this->kriteriaService = $kriteriaService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $kriterias = Kriteria::query()
            ->when($search, function ($query, $search) {
                return $query->where('kode_kriteria', 'like', "%{$search}%")
                    ->orWhere('nama_kriteria', 'like', "%{$search}%");
            })
            ->orderBy('kode_kriteria', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalBobot = $this->kriteriaService->getTotalBobot();

        return view('manajer.kriteria.index', compact('kriterias', 'search', 'totalBobot'));
    }

    public function create()
    {
        $remainingBobot = $this->kriteriaService->getRemainingBobot();
        return view('manajer.kriteria.create', compact('remainingBobot'));
    }

    public function store(StoreKriteriaRequest $request)
    {
        Kriteria::create($request->validated());
        return redirect()->route('manajer.kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Kriteria $kriteria)
    {
        $remainingBobot = $this->kriteriaService->getRemainingBobot($kriteria->kriteria_id) + $kriteria->bobot;
        return view('manajer.kriteria.edit', compact('kriteria', 'remainingBobot'));
    }

    public function update(UpdateKriteriaRequest $request, Kriteria $kriteria)
    {
        $kriteria->update($request->validated());
        return redirect()->route('manajer.kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();
        return redirect()->route('manajer.kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
