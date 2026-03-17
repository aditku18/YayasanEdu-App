@extends('layouts.dashboard')

@section('title', 'Profil Unit Sekolah')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Profil Unit Sekolah</h1>
            <p class="text-slate-500 mt-1">Kelola informasi identitas dan data {{ $school->name }}.</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl flex items-center gap-4 text-emerald-600">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-100 p-6 rounded-3xl flex items-center gap-4 text-rose-600">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-bold text-sm">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="bg-white rounded-3xl border border-slate-100 p-2 premium-shadow">
        <nav class="flex gap-2" aria-label="Tabs">
            <button type="button" onclick="switchTab('view')" id="tab-view" class="tab-btn flex-1 py-4 px-6 rounded-2xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Lihat Profil
            </button>
            <button type="button" onclick="switchTab('edit')" id="tab-edit" class="tab-btn flex-1 py-4 px-6 rounded-2xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profil
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div id="tab-contents">
        <!-- View Mode -->
        <div id="view-content" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- School Info Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Main Info -->
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow">
                        <div class="flex items-center gap-6 mb-8">
                            @if($school->logo)
                            <img src="{{ $school->logo }}" alt="{{ $school->name }}" class="w-20 h-20 rounded-2xl object-cover">
                            @else
                            <div class="w-20 h-20 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center font-bold text-2xl">
                                {{ substr($school->name, 0, 2) }}
                            </div>
                            @endif
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">{{ $school->name }}</h2>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                                    {{ $school->level }}
                                </span>
                                @if($school->status)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 ml-2">
                                    {{ ucfirst($school->status) }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">NPSN</p>
                                <p class="font-bold text-slate-900">{{ $school->npsn ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jenjang</p>
                                <p class="font-bold text-slate-900">{{ $school->level }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Email</p>
                                <p class="font-bold text-slate-900">{{ $school->email }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Telepon</p>
                                <p class="font-bold text-slate-900">{{ $school->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Alamat</h3>
                        </div>
                        <p class="text-slate-700 leading-relaxed">{{ $school->address }}</p>
                        <div class="mt-4 flex flex-wrap gap-4">
                            <span class="text-sm font-bold text-slate-500">{{ $school->district }}, {{ $school->city }}</span>
                            <span class="text-sm font-bold text-slate-500">{{ $school->province }} {{ $school->postal_code }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Stats -->
                <div class="space-y-6">
                    <!-- Principal Info -->
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Kepala Sekolah</h3>
                        </div>
                        <p class="font-bold text-slate-900 text-lg">{{ $school->principal_name ?? '-' }}</p>
                        <div class="mt-4 space-y-2 text-sm">
                            <p class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $school->principal_email ?? '-' }}
                            </p>
                            <p class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $school->principal_phone ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Statistik</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-slate-50 rounded-2xl">
                                <p class="text-2xl font-bold text-primary-600">{{ $school->students_count ?? 0 }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase">Siswa</p>
                            </div>
                            <div class="text-center p-4 bg-slate-50 rounded-2xl">
                                <p class="text-2xl font-bold text-primary-600">{{ $school->teachers_count ?? 0 }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase">Guru</p>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Info -->
                    @if($admin)
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Administrator</h3>
                        </div>
                        <p class="font-bold text-slate-900 text-lg">{{ $admin->name }}</p>
                        <p class="text-sm text-slate-500">{{ $admin->email }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <div id="edit-content" class="tab-content hidden">
            <form action="{{ route('tenant.school.profile.update', ['school' => $schoolSlug ?? $school->slug]) }}" method="POST" class="space-y-6">
                @csrf
                @method('POST')

                <!-- Section 1: Profil Sekolah -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center font-bold">1</div>
                        <h3 class="text-lg font-bold text-slate-900">Profil & Identitas Sekolah</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Nama Sekolah</label>
                            <input type="text" name="name" value="{{ old('name', $school->name) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('name') ring-2 ring-rose-500 @enderror">
                            @error('name') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Jenjang Sekolah</label>
                            <select name="level" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('level') ring-2 ring-rose-500 @enderror">
                                <option value="TK" {{ old('level', $school->level) == 'TK' ? 'selected' : '' }}>TK / PAUD</option>
                                <option value="SD" {{ old('level', $school->level) == 'SD' ? 'selected' : '' }}>SD / MI</option>
                                <option value="SMP" {{ old('level', $school->level) == 'SMP' ? 'selected' : '' }}>SMP / MTs</option>
                                <option value="SMA" {{ old('level', $school->level) == 'SMA' ? 'selected' : '' }}>SMA / MA</option>
                                <option value="SMK" {{ old('level', $school->level) == 'SMK' ? 'selected' : '' }}>SMK</option>
                            </select>
                            @error('level') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">NPSN</label>
                            <input type="text" name="npsn" value="{{ old('npsn', $school->npsn) }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('npsn') ring-2 ring-rose-500 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Email Sekolah</label>
                            <input type="email" name="email" value="{{ old('email', $school->email) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('email') ring-2 ring-rose-500 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $school->phone) }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('phone') ring-2 ring-rose-500 @enderror">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Alamat Lengkap -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold">2</div>
                        <h3 class="text-lg font-bold text-slate-900">Lokasi & Alamat</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap</label>
                            <textarea name="address" required rows="3" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('address') ring-2 ring-rose-500 @enderror">{{ old('address', $school->address) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Provinsi</label>
                            <input type="text" name="province" value="{{ old('province', $school->province) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Kabupaten / Kota</label>
                            <input type="text" name="city" value="{{ old('city', $school->city) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Kecamatan</label>
                            <input type="text" name="district" value="{{ old('district', $school->district) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $school->postal_code) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Kepala Sekolah -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-bold">3</div>
                        <h3 class="text-lg font-bold text-slate-900">Data Kepala Sekolah</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Nama Lengkap Kepala Sekolah</label>
                            <input type="text" name="principal_name" value="{{ old('principal_name', $school->principal_name) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Email Kepala Sekolah</label>
                            <input type="email" name="principal_email" value="{{ old('principal_email', $school->principal_email) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">No. HP Kepala Sekolah</label>
                            <input type="text" name="principal_phone" value="{{ old('principal_phone', $school->principal_phone) }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Akun Administrator Unit -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center font-bold">4</div>
                        <h3 class="text-lg font-bold text-slate-900">Akun Administrator Unit</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Nama Administrator</label>
                            <input type="text" name="admin_name" value="{{ old('admin_name', $admin ? $admin->name : '') }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('admin_name') ring-2 ring-rose-500 @enderror">
                            @error('admin_name') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Email Login Admin</label>
                            <input type="email" name="admin_email" value="{{ old('admin_email', $admin ? $admin->email : '') }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('admin_email') ring-2 ring-rose-500 @enderror">
                            @error('admin_email') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="submit" class="px-10 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.tab-btn {
    @apply text-slate-500 hover:text-slate-700;
}
.tab-btn.active {
    @apply bg-primary-50 text-primary-600;
}
.tab-content.hidden {
    display: none;
}
</style>

<script>
function switchTab(tab) {
    // Update button states
    document.getElementById('tab-view').classList.remove('active');
    document.getElementById('tab-edit').classList.remove('active');
    document.getElementById('tab-' + tab).classList.add('active');
    
    // Update content visibility
    document.getElementById('view-content').classList.add('hidden');
    document.getElementById('edit-content').classList.add('hidden');
    document.getElementById(tab + '-content').classList.remove('hidden');
}

// Initialize with view tab active
document.addEventListener('DOMContentLoaded', function() {
    switchTab('view');
});
</script>
@endsection
