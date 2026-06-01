<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('manajer.observasi.index') }}" class="text-gray-400 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-base-dark leading-tight">
                {{ __('Detail Observasi') }} - <span class="text-primary">{{ $observasi->lokasi->nama_lokasi ?? '-' }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Data Details -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Info Utama -->
                <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-base-dark">Informasi Bangunan</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Jenis Bangunan</p>
                                <p class="text-sm font-bold text-base-dark">{{ $observasi->jenis_bangunan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Luas Tanah</p>
                                <p class="text-sm font-bold text-base-dark">{{ $observasi->luas_tanah }} m²</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Luas Bangunan</p>
                                <p class="text-sm font-bold text-base-dark">{{ $observasi->luas_bangunan }} m²</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Jumlah Ruangan</p>
                                <p class="text-sm font-bold text-base-dark">{{ $observasi->jumlah_ruangan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Jumlah WC</p>
                                <p class="text-sm font-bold text-base-dark">{{ $observasi->jumlah_wc }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Sumber Air & Listrik</p>
                                <p class="text-sm font-bold text-base-dark">{{ $observasi->sumber_air }} / {{ $observasi->listrik ? 'PLN' : 'Tidak Ada' }}</p>
                            </div>
                        </div>

                        @if($observasi->catatan)
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 font-medium mb-1">Catatan Observer</p>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $observasi->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Hasil Penilaian (TOPSIS View) -->
                <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-base-dark">Data Penilaian TOPSIS</h3>
                        @if($observasi->penilaians->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Tersinkronisasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Belum Disinkron
                            </span>
                        @endif
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria / Indikator</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Observasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Harga Sewa / Tahun</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-base-dark text-right">Rp {{ number_format($observasi->harga_sewa, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Kepadatan Penduduk</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-base-dark text-right">{{ $observasi->kepadatan_penduduk }} jiwa/km²</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Jumlah Kompetitor</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-base-dark text-right">{{ $observasi->jumlah_kompetitor }} titik</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Jarak ke RPH</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-base-dark text-right">{{ $observasi->jarak_rph }} km</td>
                                </tr>
                                
                                <!-- Aksesibilitas (Calculated) -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-700 mb-1">Skor Aksesibilitas (Sistem)</div>
                                        <ul class="text-xs text-gray-500 space-y-1 ml-4 list-disc">
                                            <li class="{{ $observasi->akses_roda4 ? 'text-primary' : 'text-gray-400 line-through' }}">Akses Roda 4</li>
                                            <li class="{{ $observasi->jalan_bagus ? 'text-primary' : 'text-gray-400 line-through' }}">Jalan Bagus</li>
                                            <li class="{{ $observasi->dekat_fasilitas ? 'text-primary' : 'text-gray-400 line-through' }}">Dekat Fasilitas</li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right align-top pt-5">
                                        @php
                                            $aksesScore = 0;
                                            $trues = ($observasi->akses_roda4 ? 1 : 0) + ($observasi->jalan_bagus ? 1 : 0) + ($observasi->dekat_fasilitas ? 1 : 0);
                                            $aksesScore = match($trues) { 3 => 5, 2 => 3, 1 => 1, default => 0 };
                                        @endphp
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary text-white font-bold text-sm">
                                            {{ $aksesScore }}
                                        </span>
                                    </td>
                                </tr>

                                <!-- Kelayakan (Calculated) -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-700 mb-1">Skor Kelayakan Bangunan (Sistem)</div>
                                        <ul class="text-xs text-gray-500 space-y-1 ml-4 list-disc">
                                            <li class="{{ $observasi->bangunan_layak ? 'text-primary' : 'text-gray-400 line-through' }}">Struktur Layak</li>
                                            <li class="{{ $observasi->ventilasi_baik ? 'text-primary' : 'text-gray-400 line-through' }}">Ventilasi Baik</li>
                                            <li class="{{ $observasi->air_listrik_memadai ? 'text-primary' : 'text-gray-400 line-through' }}">Air/Listrik Memadai</li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right align-top pt-5">
                                        @php
                                            $layakScore = 0;
                                            $ltrues = ($observasi->bangunan_layak ? 1 : 0) + ($observasi->ventilasi_baik ? 1 : 0) + ($observasi->air_listrik_memadai ? 1 : 0);
                                            $layakScore = match($ltrues) { 3 => 5, 2 => 3, 1 => 1, default => 0 };
                                        @endphp
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary text-white font-bold text-sm">
                                            {{ $layakScore }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Metadata & Photos -->
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Metadata</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Lokasi Alternatif</p>
                            <a href="{{ route('manajer.lokasi.show', $observasi->lokasi_id) }}" class="text-sm font-semibold text-primary hover:underline">
                                {{ $observasi->lokasi->nama_lokasi ?? '-' }}
                            </a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Observer (Pemeriksa)</p>
                            <div class="flex items-center text-sm font-semibold text-base-dark">
                                <div class="w-6 h-6 rounded-full bg-soft-green text-primary flex items-center justify-center text-xs mr-2">
                                    {{ substr($observasi->user->name ?? 'S', 0, 1) }}
                                </div>
                                {{ $observasi->user->name ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Observasi</p>
                            <p class="text-sm font-medium text-base-dark">{{ $observasi->tanggal_observasi->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Image Gallery -->
                <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-bold text-base-dark">Dokumentasi ({{ $observasi->dokumentasiLokasis->count() }})</h3>
                    </div>
                    <div class="p-5" x-data="{ imgModal: false, imgModalSrc: '' }">
                        @if($observasi->dokumentasiLokasis->count() > 0)
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($observasi->dokumentasiLokasis as $doc)
                                    <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 cursor-pointer bg-gray-100"
                                         @click="imgModalSrc = '{{ asset('storage/' . $doc->foto_path) }}'; imgModal = true;">
                                        <img src="{{ asset('storage/' . $doc->foto_path) }}" loading="lazy" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" alt="Dokumentasi Observasi">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs text-gray-500">Tidak ada foto dokumentasi.</p>
                            </div>
                        @endif

                        <!-- Lightbox Modal -->
                        <div x-show="imgModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90" @keydown.escape.window="imgModal = false">
                            <div class="relative max-w-4xl w-full max-h-screen flex items-center justify-center" @click.away="imgModal = false">
                                <button @click="imgModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <img :src="imgModalSrc" class="max-h-[85vh] max-w-full rounded shadow-2xl object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
