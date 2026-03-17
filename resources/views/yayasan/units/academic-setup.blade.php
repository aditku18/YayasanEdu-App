@extends('layouts.dashboard')

@section('title', 'Academic Setup - ' . $school->name)

@section('content')
<div class="space-y-8" x-data="{ 
    activeTab: 'majors', 
    showMajorModal: false, 
    showAYModal: false,
    editMode: false, 
    majorData: { id: '', code: '', name: '', abbreviation: '', head_of_major: '', description: '', capacity: '', _method: 'POST' },
    ayData: { id: '', name: '', semester: '', is_active: false, _method: 'POST' },
    openCreateMajor() {
        this.editMode = false;
        this.majorData = { id: '', code: '', name: '', abbreviation: '', head_of_major: '', description: '', capacity: '', _method: 'POST' };
        this.showMajorModal = true;
    },
    openEditMajor(major) {
        this.editMode = true;
        this.majorData = { ...major, _method: 'PUT' };
        this.showMajorModal = true;
    },
    openCreateAY() {
        this.editMode = false;
        this.ayData = { id: '', name: '', semester: 'Ganjil', is_active: false, _method: 'POST' };
        this.showAYModal = true;
    },
    openEditAY(ay) {
        this.editMode = true;
        this.ayData = { ...ay, _method: 'PUT' };
        this.showAYModal = true;
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center overflow-hidden">
                @if($school->logo)
                    <img src="{{ tenant_asset('storage/' . $school->logo) }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <div class="text-2xl font-bold text-primary-600">{{ substr($school->name, 0, 1) }}</div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $school->name }}</h1>
                <div class="flex items-center gap-2 text-slate-500 text-sm mt-1">
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold uppercase tracking-wider">{{ $school->level }}</span>
                    <span>•</span>
                    <span>{{ $school->city }}, {{ $school->province }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tenant.units.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-all border border-slate-200">
                Kembali
            </a>
            <button class="px-5 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Guru / Siswa
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-2xl w-fit">
        <button @click="activeTab = 'majors'" :class="activeTab === 'majors' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Manajemen Jurusan</button>
        <button @click="activeTab = 'academic_years'" :class="activeTab === 'academic_years' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Tahun Ajaran</button>
        <button @click="activeTab = 'subjects'" :class="activeTab === 'subjects' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Mata Pelajaran</button>
        <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Kurikulum</button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tab: Manajemen Jurusan -->
            <div x-show="activeTab === 'majors'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4">
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Daftar Jurusan</h3>
                            <p class="text-xs text-slate-500 mt-1">Kelola kompetensi keahlian yang tersedia di unit ini.</p>
                        </div>
                        <button @click="openCreateMajor()" class="p-2 bg-primary-50 text-primary-600 rounded-xl hover:bg-primary-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode & Nama</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Kelas</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Daya Tampung</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($majors as $major)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $major->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $major->code }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-600">0</td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-600">{{ $major->capacity ?? '∞' }}</td>
                                    <td class="px-6 py-5">
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEditMajor({{ $major }})" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form action="{{ route('tenant.majors.destroy', $major) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-400">Belum ada data jurusan</p>
                                                <button @click="openCreateMajor()" class="text-primary-600 text-xs font-bold mt-1 hover:underline">Klik di sini untuk menambah</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Tahun Ajaran -->
            <div x-show="activeTab === 'academic_years'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4">
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Tahun Ajaran</h3>
                            <p class="text-xs text-slate-500 mt-1">Kelola periode akademik aktif untuk unit ini.</p>
                        </div>
                        <button @click="openCreateAY()" class="p-2 bg-primary-50 text-primary-600 rounded-xl hover:bg-primary-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tahun Ajaran</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Semester</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($academicYears as $ay)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="font-bold text-slate-900">{{ $ay->name }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-medium text-slate-600">{{ $ay->semester }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($ay->is_active)
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                        @else
                                            <form action="{{ route('tenant.academic-years.set-active', $ay) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[10px] font-bold text-primary-600 hover:underline">Aktifkan</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEditAY({{ $ay }})" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form action="{{ route('tenant.academic-years.destroy', $ay) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold">Belum ada data tahun ajaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div x-show="activeTab === 'subjects'" x-cloak class="bg-white p-12 rounded-[2rem] border border-slate-100 shadow-sm text-center">
                <h3 class="font-bold text-slate-900">Mata Pelajaran</h3>
                <p class="text-sm text-slate-500 mt-2">Segera hadir: Daftar pelajaran per unit.</p>
            </div>
            <div x-show="activeTab === 'curriculum'" x-cloak class="bg-white p-12 rounded-[2rem] border border-slate-100 shadow-sm text-center">
                <h3 class="font-bold text-slate-900">Kurikulum</h3>
                <p class="text-sm text-slate-500 mt-2">Segera hadir: Pengaturan standar kompetensi.</p>
            </div>

        </div>

        <!-- Sidebar: Info & Statistics -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-[2rem] p-8 text-white shadow-xl shadow-primary-200">
                <h3 class="font-bold text-lg mb-4">Statistik Unit</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl backdrop-blur-md">
                        <span class="text-sm font-medium text-primary-100">Total Guru</span>
                        <span class="text-xl font-bold">0</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl backdrop-blur-md">
                        <span class="text-sm font-medium text-primary-100">Total Siswa</span>
                        <span class="text-xl font-bold">0</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl backdrop-blur-md">
                        <span class="text-sm font-medium text-primary-100">Total Kelas</span>
                        <span class="text-xl font-bold">0</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-900 border-b border-slate-50 pb-4 mb-4">Informasi Unit</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kepala Sekolah</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $school->principal_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tahun Ajaran Aktif</p>
                        @php $activeYear = $academicYears->where('is_active', true)->first(); @endphp
                        <p class="text-sm font-bold text-primary-600 mt-1">{{ $activeYear ? $activeYear->name : 'Harap Set Tahun Ajaran' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Metode Penilaian</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">Kurikulum Merdeka</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Major Create/Edit -->
    <div x-show="showMajorModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div @click.away="showMajorModal = false" class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl transform transition-all">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900" x-text="editMode ? 'Edit Jurusan' : 'Tambah Jurusan Baru'"></h3>
                <button @click="showMajorModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="editMode ? `/yayasan/majors/${majorData.id}` : `{{ route('tenant.majors.store', $school) }}`" method="POST" class="p-8 space-y-6">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Kode Jurusan</label>
                        <input type="text" name="code" x-model="majorData.code" required class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Misal: TKR">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Singkatan</label>
                        <input type="text" name="abbreviation" x-model="majorData.abbreviation" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Misal: TKR">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Nama Lengkap Jurusan</label>
                    <input type="text" name="name" x-model="majorData.name" required class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Misal: Teknik Kendaraan Ringan">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Kepala Jurusan</label>
                    <input type="text" name="head_of_major" x-model="majorData.head_of_major" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Nama Guru Kaprodi">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Daya Tampung per Tahun (Opsional)</label>
                    <input type="number" name="capacity" min="1" x-model="majorData.capacity" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Isi angka (misal: 100), biarkan kosong jika tidak dibatasi">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" @click="showMajorModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                        Simpan Jurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal: Academic Year Create/Edit -->
    <div x-show="showAYModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div @click.away="showAYModal = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900" x-text="editMode ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran'"></h3>
                <button @click="showAYModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="editMode ? `/yayasan/academic-years/${ayData.id}` : `{{ route('tenant.academic-years.store') }}`" method="POST" class="p-8 space-y-6">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Tahun Ajaran</label>
                    <input type="text" name="name" x-model="ayData.name" required class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Misal: 2024/2025">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Semester</label>
                    <select name="semester" x-model="ayData.semester" required class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" name="is_active" x-model="ayData.is_active" value="1" class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                    <label class="text-xs font-bold text-slate-600">Set sebagai tahun ajaran aktif</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" @click="showAYModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
