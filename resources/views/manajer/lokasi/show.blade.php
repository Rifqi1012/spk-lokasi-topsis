<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('manajer.lokasi.index') }}" class="text-gray-400 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-base-dark leading-tight">
                {{ __('Detail Lokasi') }} - <span class="text-primary">{{ $lokasi->nama_lokasi }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
            
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Detail Informasi -->
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-base-dark border-b border-gray-100 pb-2 mb-4">Informasi Dasar</h3>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-500">Nama Lokasi</div>
                                    <div class="col-span-2 text-sm font-semibold text-base-dark">{{ $lokasi->nama_lokasi }}</div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-500">Alamat Lengkap</div>
                                    <div class="col-span-2 text-sm text-base-dark">{{ $lokasi->alamat }}</div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-500">Kecamatan</div>
                                    <div class="col-span-2 text-sm text-base-dark">{{ $lokasi->kecamatan }}</div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-500">Kabupaten/Kota</div>
                                    <div class="col-span-2 text-sm text-base-dark">{{ $lokasi->kabupaten }}</div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-500">Provinsi</div>
                                    <div class="col-span-2 text-sm text-base-dark">{{ $lokasi->provinsi }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Read-only Map Integration -->
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-base-dark flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Peta Lokasi
                                </h3>
                                @if($lokasi->latitude && $lokasi->longitude)
                                    <a href="https://www.google.com/maps?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-md text-xs font-semibold transition-colors">
                                        Buka di Google Maps
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
                            
                            @if($lokasi->latitude && $lokasi->longitude)
                                <div class="relative w-full rounded-lg border border-gray-300 shadow-sm overflow-hidden h-[300px] md:h-[350px] lg:h-[450px]">
                                    <div id="map-readonly-{{ $lokasi->id }}" class="w-full h-full z-0 relative bg-gray-50"></div>
                                </div>
                                <div class="mt-3 flex gap-4 text-sm">
                                    <div class="bg-gray-50 px-3 py-2 rounded-md border border-gray-200">
                                        <span class="font-medium text-gray-500">Latitude:</span> <span class="font-mono text-gray-800">{{ $lokasi->latitude }}</span>
                                    </div>
                                    <div class="bg-gray-50 px-3 py-2 rounded-md border border-gray-200">
                                        <span class="font-medium text-gray-500">Longitude:</span> <span class="font-mono text-gray-800">{{ $lokasi->longitude }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-full flex flex-col items-center justify-center p-8 bg-gray-50 border border-gray-200 rounded-lg text-center">
                                    <div class="w-16 h-16 mb-4 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <line x1="4" y1="4" x2="20" y2="20" stroke-width="2" stroke-linecap="round"></line>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Koordinat Lokasi Belum Tersedia</h4>
                                    <p class="text-xs text-gray-500 max-w-sm">Peta tidak dapat ditampilkan karena titik koordinat latitude dan longitude belum diinputkan untuk lokasi ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Side Panel / Metadata -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Metadata</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Didaftarkan Oleh</p>
                                    <div class="flex items-center text-sm font-semibold text-base-dark">
                                        <div class="w-6 h-6 rounded-full bg-soft-green text-primary flex items-center justify-center text-xs mr-2">
                                            {{ substr($lokasi->creator->name ?? 'S', 0, 1) }}
                                        </div>
                                        {{ $lokasi->creator->name ?? 'Sistem' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Pendaftaran</p>
                                    <p class="text-sm font-medium text-base-dark">{{ $lokasi->created_at->format('d M Y, H:i') }}</p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Terakhir Diupdate</p>
                                    <p class="text-sm font-medium text-base-dark">{{ $lokasi->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ route('manajer.lokasi.edit', $lokasi) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Edit Lokasi
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
    @if($lokasi->latitude && $lokasi->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        [id^="map-"] { min-height: 300px; }
    </style>
    @endif
    @endpush

    @push('scripts')
    @if($lokasi->latitude && $lokasi->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapContainer = document.getElementById('map-readonly-{{ $lokasi->id }}');
            if (!mapContainer || mapContainer._leaflet_id) return;
            
            const map = L.map(mapContainer, {
                dragging: false,
                scrollWheelZoom: false,
                touchZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false,
                zoomControl: true
            }).setView([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            
            const greenIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], 
                iconAnchor: [12, 41], 
                popupAnchor: [1, -34], 
                shadowSize: [41, 41]
            });
            
            L.marker([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], { icon: greenIcon }).addTo(map);
            
            setTimeout(() => map.invalidateSize(), 200);
        });
    </script>
    @endif
    @endpush
</x-app-layout>
