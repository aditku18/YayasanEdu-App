<x-platform-layout>
    <x-slot name="header">Dashboard Overview</x-slot>
<div class="space-y-8">
    
    <!-- Header & Trial Alert -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-slate-500 mt-1">Berikut ringkasan aktivitas dan data yayasan Anda untuk hari ini.</p>
        </div>
        
        @if(isset($isTrial) && $isTrial)
        <div class="bg-indigo-600 rounded-2xl p-1 pr-4 flex items-center gap-4 text-white shadow-xl shadow-indigo-200">
            <div class="bg-white/20 p-2 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-bold tracking-widest opacity-80">Trial Account</span>
                <span class="text-sm font-bold">{{ $trialDaysLeft }} Hari Tersisa</span>
            </div>
            <button class="ml-4 px-4 py-2 bg-white text-indigo-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors">
                Upgrade Sekarang
            </button>
        </div>
        @endif
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Schools -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow group hover:border-primary-500 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Unit Sekolah</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_schools'] }}</h3>
        </div>

        <!-- Students -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow group hover:border-emerald-500 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+1.2k</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Siswa</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_students']) }}</h3>
        </div>

        <!-- Teachers -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow group hover:border-amber-500 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Stabil</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Guru</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_teachers'] }}</h3>
        </div>

        <!-- Classes -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow group hover:border-rose-500 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Kelas</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_classes'] }}</h3>
        </div>
    </div>

    <!-- Main Grid: Units & Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- School Units List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">Unit Sekolah Anda</h2>
                <a href="#" class="text-sm font-bold text-primary-600 hover:text-primary-700">Lihat Semua →</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($schools as $school)
                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 premium-shadow hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded-full tracking-wider">Aktif</span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900">{{ $school->name }}</h3>
                    <p class="text-slate-400 text-sm mt-1">Jenjang: SMA / SMK</p>
                    
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Siswa</p>
                            <p class="text-sm font-extrabold text-slate-700">{{ $school->students_count }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Kelas</p>
                            <p class="text-sm font-extrabold text-slate-700">{{ $school->class_rooms_count }}</p>
                        </div>
                    </div>

                    <a href="{{ route('tenant.select-unit', $school->id) }}" class="mt-6 flex items-center justify-center gap-2 w-full py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-primary-600 transition-all shadow-lg shadow-slate-200 active:scale-95">
                        Masuk Unit →
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Distribution Chart & Activity -->
        <div class="space-y-8">
            <!-- Distribution Chart -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-900">Distribusi Siswa</h3>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                </div>
                <div id="distributionChart" class="min-h-[200px]"></div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
                <h3 class="font-bold text-slate-900 mb-6">Aktivitas Terbaru</h3>
                <div class="space-y-6">
                    @foreach($activities as $activity)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex-shrink-0 flex items-center justify-center text-slate-400">
                            @if($activity['type'] == 'school')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ $activity['description'] }}</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="w-full mt-6 py-2 text-xs font-bold text-slate-400 hover:text-primary-600 transition-colors">Lihat Semua Aktivitas</button>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                name: 'Siswa',
                data: [{{ implode(',', $schools->pluck('students_count')->toArray()) }}]
            }],
            chart: {
                type: 'bar',
                height: 220,
                toolbar: { show: false },
                sparkline: { enabled: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    columnWidth: '40%',
                    distributed: true,
                }
            },
            dataLabels: { enabled: false },
            colors: ['#0ea5e9', '#6366f1', '#f59e0b', '#10b981'],
            grid: {
                show: false,
                padding: { left: 0, right: 0 }
            },
            xaxis: {
                categories: {!! json_encode($schools->pluck('name')->toArray()) !!},
                labels: { show: false },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: false },
            legend: { show: false }
        };

        var chart = new ApexCharts(document.querySelector("#distributionChart"), options);
        chart.render();
    });
</script>
@endpush

</x-platform-layout>
