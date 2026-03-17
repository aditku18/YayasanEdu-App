@extends('layouts.tenant-platform')

@section('title', 'Edit Unit Sekolah')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Profil Unit Sekolah</h1>
            <p class="text-slate-500 mt-1">Perbarui informasi identitas dan operasional unit sekolah.</p>
        </div>
        <a href="{{ route('tenant.units.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Kembali ke Daftar</a>
    </div>

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-100 p-6 rounded-3xl flex items-center gap-4 text-rose-600">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-bold text-sm">{{ session('error') }}</p>
    </div>
    @endif

    <form action="{{ route('tenant.units.update', $school) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Section 1: Profil Sekolah -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center font-bold text-xl">1</div>
                <h3 class="text-xl font-bold text-slate-900">Profil & Identitas Sekolah</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold text-xl">2</div>
                <h3 class="text-xl font-bold text-slate-900">Lokasi & Alamat</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center font-bold text-xl">3</div>
                <h3 class="text-xl font-bold text-slate-900">Data Kepala Sekolah</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center font-bold text-xl">4</div>
                <h3 class="text-xl font-bold text-slate-900">Akun Administrator Unit</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="admin_password" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('admin_password') ring-2 ring-rose-500 @enderror">
                    @error('admin_password') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Konfirmasi Password</label>
                    <input type="password" name="admin_password_confirmation" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
            </div>
            <div class="bg-violet-50 p-6 rounded-3xl border border-violet-100 flex items-start gap-4 text-violet-700">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs font-bold leading-relaxed">Administrator unit ini akan memiliki akses penuh ke manajemen operasional sekolah seperti manajemen siswa, guru, dan nilai.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-8">
            <a href="{{ route('tenant.units.index') }}" class="px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition-all">Batal</a>
            <button type="submit" class="px-12 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
