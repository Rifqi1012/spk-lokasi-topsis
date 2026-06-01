<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-dark leading-tight">
            {{ __('Dashboard') }} <span class="text-gray-400 text-sm ml-2 font-normal">Sistem Pendukung Keputusan Lokasi Cabang</span>
        </h2>
    </x-slot>

    <!-- Welcome Alert -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-base-dark">Selamat Datang, {{ Auth::user()->name }}!</h3>
            <p class="text-base-medium text-sm mt-1">Anda login sebagai <span class="font-semibold text-primary">{{ ucfirst(Auth::user()->roles->first()?->name ?? 'User') }}</span>. Sistem siap digunakan untuk analisis TOPSIS.</p>
        </div>
        <div class="hidden sm:block">
            <div class="w-12 h-12 bg-soft-green rounded-full flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-base-medium">Total Alternatif</p>
                <p class="text-2xl font-bold text-base-dark">{{ \App\Models\Lokasi::count() }}</p>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-base-medium">Observasi Lokasi</p>
                <p class="text-2xl font-bold text-base-dark">{{ \App\Models\ObservasiLokasi::count() }}</p>
            </div>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-base-medium">Kriteria Aktif</p>
                <p class="text-2xl font-bold text-base-dark">{{ \App\Models\Kriteria::count() }}</p>
            </div>
        </div>
        
        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-base-medium">Total Perhitungan</p>
                <p class="text-2xl font-bold text-base-dark">{{ \App\Models\HasilPerhitungan::count() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-base-dark">Aksi Cepat</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            
            @can('manage lokasi')
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg border border-gray-100 hover:bg-soft-green hover:border-primary transition group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 group-hover:text-primary shadow-sm mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-sm font-medium text-base-dark group-hover:text-primary">Tambah Lokasi</span>
            </a>
            @endcan
            
            @can('manage observasi')
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg border border-gray-100 hover:bg-soft-green hover:border-primary transition group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 group-hover:text-primary shadow-sm mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <span class="text-sm font-medium text-base-dark group-hover:text-primary">Input Observasi</span>
            </a>
            @endcan

            @can('process perhitungan')
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg border border-gray-100 hover:bg-soft-green hover:border-primary transition group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 group-hover:text-primary shadow-sm mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-sm font-medium text-base-dark group-hover:text-primary">Proses TOPSIS</span>
            </a>
            @endcan
            
            @can('view rekomendasi')
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg border border-gray-100 hover:bg-soft-green hover:border-primary transition group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 group-hover:text-primary shadow-sm mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-sm font-medium text-base-dark group-hover:text-primary">Lihat Laporan</span>
            </a>
            @endcan
        </div>
    </div>
</x-app-layout>
