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

                        <!-- FUTURE MAP INTEGRATION PLACEHOLDER -->
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-base-dark mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Peta Lokasi
                            </h3>
                            
                            @if($lokasi->latitude && $lokasi->longitude)
                                <div class="w-full h-64 bg-gray-100 rounded-lg border border-gray-200 flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    <span class="text-sm">Peta interaktif akan ditampilkan di sini di masa mendatang.</span>
                                    <span class="text-xs text-gray-400 mt-1">Lat: {{ $lokasi->latitude }}, Lng: {{ $lokasi->longitude }}</span>
                                </div>
                            @else
                                <div class="w-full p-4 bg-yellow-50 border border-yellow-100 rounded-lg text-sm text-yellow-700 flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Koordinat latitude dan longitude belum diisi. Peta tidak dapat ditampilkan.</span>
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
</x-app-layout>
