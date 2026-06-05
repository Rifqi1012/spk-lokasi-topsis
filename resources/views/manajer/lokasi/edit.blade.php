<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-dark leading-tight">
            {{ __('Edit Lokasi') }} - <span class="text-primary">{{ $lokasi->nama_lokasi }}</span>
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6" x-data="wilayahDropdown()">
        <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
            <div class="p-8">
                
                <form method="POST" action="{{ route('manajer.lokasi.update', $lokasi) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        
                        <!-- Nama Lokasi -->
                        <div>
                            <label for="nama_lokasi" class="block text-sm font-medium text-base-dark mb-1">Nama Lokasi / Alternatif</label>
                            <input id="nama_lokasi" type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3">
                            <x-input-error :messages="$errors->get('nama_lokasi')" class="mt-2" />
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-base-dark mb-1">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" required rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3">{{ old('alamat', $lokasi->alamat) }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <!-- Wilayah Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Provinsi -->
                            <div>
                                <label class="block text-sm font-medium text-base-dark mb-1">Provinsi</label>
                                
                                <template x-if="!selectedProvince && legacyProvinsi">
                                    <div class="text-xs text-yellow-600 mb-1 flex items-center bg-yellow-50 p-1 rounded">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Lama: <span class="font-semibold ml-1" x-text="legacyProvinsi"></span>
                                    </div>
                                </template>
                                
                                <select 
                                    x-model="selectedProvince" 
                                    @change="fetchRegencies; selectedProvinceName = $event.target.options[$event.target.selectedIndex].text; selectedRegency = ''; selectedRegencyName = ''; selectedDistrict = ''; selectedDistrictName = '';" 
                                    :required="!legacyProvinsi"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 bg-white"
                                >
                                    <option value="">-- Update Provinsi --</option>
                                    <template x-for="prov in provinces" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="province_id" :value="selectedProvince">
                                <input type="hidden" name="provinsi" :value="selectedProvince ? selectedProvinceName : legacyProvinsi">
                                <x-input-error :messages="$errors->get('provinsi')" class="mt-2" />
                            </div>

                            <!-- Kabupaten -->
                            <div>
                                <label class="block text-sm font-medium text-base-dark mb-1">Kabupaten/Kota</label>
                                
                                <template x-if="!selectedRegency && legacyKabupaten">
                                    <div class="text-xs text-yellow-600 mb-1 flex items-center bg-yellow-50 p-1 rounded">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Lama: <span class="font-semibold ml-1" x-text="legacyKabupaten"></span>
                                    </div>
                                </template>
                                
                                <select 
                                    x-model="selectedRegency" 
                                    @change="fetchDistricts; selectedRegencyName = $event.target.options[$event.target.selectedIndex].text; selectedDistrict = ''; selectedDistrictName = '';" 
                                    :required="!legacyKabupaten || selectedProvince !== ''"
                                    :disabled="regencies.length === 0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 bg-white disabled:bg-gray-100 disabled:text-gray-400"
                                >
                                    <option value="">-- Update Kabupaten --</option>
                                    <template x-for="reg in regencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.name"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="regency_id" :value="selectedRegency">
                                <input type="hidden" name="kabupaten" :value="selectedRegency ? selectedRegencyName : legacyKabupaten">
                                <x-input-error :messages="$errors->get('kabupaten')" class="mt-2" />
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label class="block text-sm font-medium text-base-dark mb-1">Kecamatan</label>
                                
                                <template x-if="!selectedDistrict && legacyKecamatan">
                                    <div class="text-xs text-yellow-600 mb-1 flex items-center bg-yellow-50 p-1 rounded">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Lama: <span class="font-semibold ml-1" x-text="legacyKecamatan"></span>
                                    </div>
                                </template>
                                
                                <select 
                                    x-model="selectedDistrict" 
                                    @change="selectedDistrictName = $event.target.options[$event.target.selectedIndex].text" 
                                    :required="!legacyKecamatan || selectedRegency !== ''"
                                    :disabled="districts.length === 0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 bg-white disabled:bg-gray-100 disabled:text-gray-400"
                                >
                                    <option value="">-- Update Kecamatan --</option>
                                    <template x-for="dist in districts" :key="dist.id">
                                        <option :value="dist.id" x-text="dist.name"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="district_id" :value="selectedDistrict">
                                <input type="hidden" name="kecamatan" :value="selectedDistrict ? selectedDistrictName : legacyKecamatan">
                                <x-input-error :messages="$errors->get('kecamatan')" class="mt-2" />
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Interactive Map Section -->
                        <div x-data="locationMap()" class="space-y-4">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-3 md:gap-2">
                                <div>
                                    <h3 class="text-sm font-bold text-base-dark flex items-center">
                                        Koordinat Peta
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Opsional
                                        </span>
                                    </h3>
                                    <p class="text-xs text-base-medium mt-1">Anda dapat memilih titik lokasi pada peta untuk meningkatkan akurasi lokasi.</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button type="button" @click="searchAddress" :disabled="isSearching" class="inline-flex items-center justify-center px-4 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-colors whitespace-nowrap">
                                        <svg x-show="!isSearching" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        <svg x-show="isSearching" style="display: none;" class="animate-spin w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span x-text="isSearching ? 'Mencari lokasi...' : 'Cari di Peta'"></span>
                                    </button>
                                    <button type="button" @click="getCurrentLocation" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Gunakan Lokasi Saat Ini
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Map Container -->
                            <div class="relative w-full rounded-lg border border-gray-300 shadow-sm overflow-hidden h-[300px] md:h-[350px] lg:h-[450px]" id="map-container" wire:ignore>
                                <!-- Loading State for Reverse Geocoding -->
                                <div x-show="isGeocoding" style="display: none;" class="absolute inset-0 z-[1000] bg-white/70 flex flex-col items-center justify-center backdrop-blur-sm">
                                    <svg class="animate-spin h-8 w-8 text-primary mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Mencari alamat...</span>
                                </div>
                                <div id="map" class="w-full h-full z-0 relative"></div>
                            </div>

                            <!-- Coordinate Inputs -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="latitude" :value="__('Latitude')" />
                                    <x-text-input id="latitude" class="block w-full mt-1 bg-gray-50 text-gray-500" type="text" name="latitude" x-model="lat" readonly placeholder="Opsional" />
                                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="longitude" :value="__('Longitude')" />
                                    <x-text-input id="longitude" class="block w-full mt-1 bg-gray-50 text-gray-500" type="text" name="longitude" x-model="lng" readonly placeholder="Opsional" />
                                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex items-center justify-end mt-8 pt-5 border-t border-gray-100">
                        <a href="{{ route('manajer.lokasi.index') }}" class="text-sm font-medium text-gray-500 hover:text-base-dark transition-colors mr-4">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-medium text-sm text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors shadow-sm">
                            Update Lokasi
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Alpine JS Script for Dependent Dropdown & Legacy Fallback -->
    <script>
        function wilayahDropdown() {
            return {
                provinces: [],
                regencies: [],
                districts: [],
                
                // Track IDs
                selectedProvince: '{{ old('province_id', $lokasi->province_id) }}',
                selectedRegency: '{{ old('regency_id', $lokasi->regency_id) }}',
                selectedDistrict: '{{ old('district_id', $lokasi->district_id) }}',
                
                // Track Names
                selectedProvinceName: '{{ old('provinsi', $lokasi->province_id ? $lokasi->provinsi : '') }}',
                selectedRegencyName: '{{ old('kabupaten', $lokasi->regency_id ? $lokasi->kabupaten : '') }}',
                selectedDistrictName: '{{ old('kecamatan', $lokasi->district_id ? $lokasi->kecamatan : '') }}',

                // Legacy fallbacks (when ID is null but string name exists)
                legacyProvinsi: '{{ !$lokasi->province_id ? $lokasi->provinsi : '' }}',
                legacyKabupaten: '{{ !$lokasi->regency_id ? $lokasi->kabupaten : '' }}',
                legacyKecamatan: '{{ !$lokasi->district_id ? $lokasi->kecamatan : '' }}',

                async init() {
                    let res = await fetch('/api/wilayah/provinces');
                    this.provinces = await res.json();
                    
                    if (this.selectedProvince) {
                        await this.fetchRegencies();
                    }
                    if (this.selectedRegency) {
                        await this.fetchDistricts();
                    }
                },

                async fetchRegencies() {
                    this.regencies = [];
                    this.districts = [];
                    if (!this.selectedProvince) return;
                    
                    let res = await fetch(`/api/wilayah/regencies/${this.selectedProvince}`);
                    this.regencies = await res.json();
                },

                async fetchDistricts() {
                    this.districts = [];
                    if (!this.selectedRegency) return;
                    
                    let res = await fetch(`/api/wilayah/districts/${this.selectedRegency}`);
                    this.districts = await res.json();
                }
            }
        }
    </script>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        /* Ensures map has a minimum height if tailwind classes fail */
        #map { min-height: 300px; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('locationMap', () => ({
                map: null,
                marker: null,
                lat: '{{ old('latitude', $lokasi->latitude) }}',
                lng: '{{ old('longitude', $lokasi->longitude) }}',
                isSearching: false,
                isGeocoding: false,
                geocodeTimeout: null,
                greenIcon: null,

                init() {
                    // Initialize Leaflet map
                    let defaultLat = this.lat ? parseFloat(this.lat) : -0.789;
                    let defaultLng = this.lng ? parseFloat(this.lng) : 113.921;
                    let defaultZoom = this.lat ? 13 : 5;

                    this.map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    this.greenIcon = new L.Icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });

                    if (this.lat && this.lng) {
                        this.marker = L.marker([this.lat, this.lng], { draggable: true, icon: this.greenIcon }).addTo(this.map);
                        this.setupMarkerEvents();
                    }

                    this.map.on('click', (e) => {
                        const coords = e.latlng;
                        this.updateMarker(coords.lat, coords.lng);
                    });
                },

                updateMarker(lat, lng) {
                    this.lat = lat.toFixed(6);
                    this.lng = lng.toFixed(6);
                    if (this.marker) {
                        this.marker.setLatLng([lat, lng]);
                    } else {
                        this.marker = L.marker([lat, lng], { draggable: true, icon: this.greenIcon }).addTo(this.map);
                        this.setupMarkerEvents();
                    }
                },

                updateMapFromInput() {
                    const parsedLat = parseFloat(this.lat);
                    const parsedLng = parseFloat(this.lng);
                    
                    if (!isNaN(parsedLat) && !isNaN(parsedLng) && parsedLat >= -90 && parsedLat <= 90 && parsedLng >= -180 && parsedLng <= 180) {
                        if (this.marker) {
                            this.marker.setLatLng([parsedLat, parsedLng]);
                        } else {
                            this.marker = L.marker([parsedLat, parsedLng], { draggable: true, icon: this.greenIcon }).addTo(this.map);
                            this.setupMarkerEvents();
                        }
                        this.map.setView([parsedLat, parsedLng], 15);
                    }
                },

                setupMarkerEvents() {
                    this.marker.on('dragend', (e) => {
                        const coords = e.target.getLatLng();
                        this.lat = coords.lat.toFixed(6);
                        this.lng = coords.lng.toFixed(6);
                    });
                },

                async searchAddress() {
                    const alamatInput = document.getElementById('alamat')?.value || '';
                    // For selects, their value is the ID, but we want the text
                    const provinsiSelect = document.querySelector('select[name="province_id"]');
                    const kabupatenSelect = document.querySelector('select[name="regency_id"]');
                    const kecamatanSelect = document.querySelector('select[name="district_id"]');
                    
                    let provinsi = provinsiSelect && provinsiSelect.selectedIndex > 0 ? provinsiSelect.options[provinsiSelect.selectedIndex].text : '';
                    let kabupaten = kabupatenSelect && kabupatenSelect.selectedIndex > 0 ? kabupatenSelect.options[kabupatenSelect.selectedIndex].text : '';
                    let kecamatan = kecamatanSelect && kecamatanSelect.selectedIndex > 0 ? kecamatanSelect.options[kecamatanSelect.selectedIndex].text : '';
                    
                    // Allow fallback to legacy fields if the selects are empty but legacy is populated (this happens before API completes in edit view)
                    const legacyProvinsiInput = document.querySelector('input[name="provinsi"]')?.value || '';
                    const legacyKabupatenInput = document.querySelector('input[name="kabupaten"]')?.value || '';
                    const legacyKecamatanInput = document.querySelector('input[name="kecamatan"]')?.value || '';
                    
                    provinsi = provinsi || legacyProvinsiInput;
                    kabupaten = kabupaten || legacyKabupatenInput;
                    kecamatan = kecamatan || legacyKecamatanInput;
                    
                    // Cleanup common non-standard strings that break OpenStreetMap Nominatim
                    if (provinsi) {
                        provinsi = provinsi.replace(/& sekitarnya/ig, '').trim();
                    }
                    if (kabupaten) {
                        kabupaten = kabupaten.replace(/^kota /ig, '').replace(/^kabupaten /ig, '').trim();
                    }
                    
                    // Strategy 1: Full Strict Query
                    const queryPartsStrict = [alamatInput, kecamatan, kabupaten, provinsi, 'Indonesia'].filter(Boolean);
                    const queryStrict = queryPartsStrict.join(', ');
                    
                    // Strategy 2: Relaxed Query (Drop Province & Kecamatan, just Street + City)
                    const queryPartsRelaxed = [alamatInput, kabupaten, 'Indonesia'].filter(Boolean);
                    const queryRelaxed = queryPartsRelaxed.join(', ');
                    
                    if (!queryStrict || queryStrict === 'Indonesia') {
                        alert("Harap isi alamat, provinsi, kabupaten, atau kecamatan terlebih dahulu.");
                        return;
                    }
                    
                    this.isSearching = true;
                    try {
                        // Try strict search first
                        let response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(queryStrict)}`);
                        let data = await response.json();
                        
                        // If strict fails, try relaxed fallback
                        if (!data || data.length === 0) {
                            console.log("Strict search failed, trying relaxed search:", queryRelaxed);
                            response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(queryRelaxed)}`);
                            data = await response.json();
                        }
                        
                        if (data && data.length > 0) {
                            const result = data[0];
                            const lat = parseFloat(result.lat);
                            const lng = parseFloat(result.lon);
                            
                            this.map.setView([lat, lng], 15);
                            this.updateMarker(lat, lng);
                        } else {
                            alert("Lokasi tidak ditemukan. Silakan pilih titik secara manual pada peta.");
                        }
                    } catch (error) {
                        console.error("Search failed", error);
                        alert("Terjadi kesalahan saat mencari alamat.");
                    } finally {
                        this.isSearching = false;
                    }
                },

                getCurrentLocation() {
                    if ("geolocation" in navigator) {
                        navigator.geolocation.getCurrentPosition((position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            this.map.setView([lat, lng], 15);
                            this.updateMarker(lat, lng);
                            this.triggerReverseGeocode(lat, lng);
                        }, (error) => {
                            alert("Tidak dapat mengambil lokasi Anda. Silakan cari lokasi di peta secara manual.");
                        });
                    } else {
                        alert("Browser Anda tidak mendukung fitur Geolocation.");
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
