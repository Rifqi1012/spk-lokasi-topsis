<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-dark leading-tight">
            {{ __('Tambah Lokasi Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6" x-data="wilayahDropdown()">
        <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
            <div class="p-8">
                
                <form method="POST" action="{{ route('manajer.lokasi.store') }}">
                    @csrf

                    <div class="space-y-6">
                        
                        <!-- Nama Lokasi -->
                        <div>
                            <label for="nama_lokasi" class="block text-sm font-medium text-base-dark mb-1">Nama Lokasi / Alternatif</label>
                            <input id="nama_lokasi" type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}" required autofocus class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3">
                            <x-input-error :messages="$errors->get('nama_lokasi')" class="mt-2" />
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-base-dark mb-1">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" required rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3">{{ old('alamat') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <!-- Wilayah Grid with Dependent Dropdowns -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Provinsi -->
                            <div>
                                <label class="block text-sm font-medium text-base-dark mb-1">Provinsi</label>
                                <select 
                                    x-model="selectedProvince" 
                                    @change="fetchRegencies; selectedProvinceName = $event.target.options[$event.target.selectedIndex].text; selectedRegency = ''; selectedRegencyName = ''; selectedDistrict = ''; selectedDistrictName = '';" 
                                    required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 bg-white"
                                >
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="prov in provinces" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="province_id" :value="selectedProvince">
                                <input type="hidden" name="provinsi" :value="selectedProvinceName">
                                <x-input-error :messages="$errors->get('provinsi')" class="mt-2" />
                            </div>

                            <!-- Kabupaten -->
                            <div>
                                <label class="block text-sm font-medium text-base-dark mb-1">Kabupaten/Kota</label>
                                <select 
                                    x-model="selectedRegency" 
                                    @change="fetchDistricts; selectedRegencyName = $event.target.options[$event.target.selectedIndex].text; selectedDistrict = ''; selectedDistrictName = '';" 
                                    required 
                                    :disabled="regencies.length === 0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 bg-white disabled:bg-gray-100 disabled:text-gray-400"
                                >
                                    <option value="">Pilih Kabupaten</option>
                                    <template x-for="reg in regencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.name"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="regency_id" :value="selectedRegency">
                                <input type="hidden" name="kabupaten" :value="selectedRegencyName">
                                <x-input-error :messages="$errors->get('kabupaten')" class="mt-2" />
                            </div>
                            
                            <!-- Kecamatan -->
                            <div>
                                <label class="block text-sm font-medium text-base-dark mb-1">Kecamatan</label>
                                <select 
                                    x-model="selectedDistrict" 
                                    @change="selectedDistrictName = $event.target.options[$event.target.selectedIndex].text" 
                                    required 
                                    :disabled="districts.length === 0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 bg-white disabled:bg-gray-100 disabled:text-gray-400"
                                >
                                    <option value="">Pilih Kecamatan</option>
                                    <template x-for="dist in districts" :key="dist.id">
                                        <option :value="dist.id" x-text="dist.name"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="district_id" :value="selectedDistrict">
                                <input type="hidden" name="kecamatan" :value="selectedDistrictName">
                                <x-input-error :messages="$errors->get('kecamatan')" class="mt-2" />
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Geolocation (Optional for now) -->
                        <div>
                            <h3 class="text-sm font-bold text-base-dark mb-3">Koordinat Peta (Opsional)</h3>
                            <p class="text-xs text-base-medium mb-4">Bagian ini disiapkan untuk integrasi peta di masa mendatang. Anda dapat mengisinya secara manual atau membiarkannya kosong.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Latitude -->
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-base-dark mb-1">Latitude</label>
                                    <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 placeholder-gray-300" placeholder="-6.732... ">
                                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-base-dark mb-1">Longitude</label>
                                    <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3 placeholder-gray-300" placeholder="108.552...">
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
                            Simpan Lokasi
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Alpine JS Script for Dependent Dropdown -->
    <script>
        function wilayahDropdown() {
            return {
                provinces: [],
                regencies: [],
                districts: [],
                selectedProvince: '{{ old('province_id') }}',
                selectedProvinceName: '{{ old('provinsi') }}',
                selectedRegency: '{{ old('regency_id') }}',
                selectedRegencyName: '{{ old('kabupaten') }}',
                selectedDistrict: '{{ old('district_id') }}',
                selectedDistrictName: '{{ old('kecamatan') }}',

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
</x-app-layout>
