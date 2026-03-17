@extends('layouts.ppdb')

@section('title', 'Pendaftaran — ' . $wave->name)

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    step: 1, 
    steps: [
        { id: 1, title: 'Biodata', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
        { id: 2, title: 'Keluarga', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
        { id: 3, title: 'Akademik', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253' }
    ]
}">
    <!-- Header -->
    <div class="mb-12 text-center space-y-4">
        <h1 class="text-4xl font-display font-black text-slate-900">Formulir Pendaftaran</h1>
        <p class="text-slate-500 font-medium">Gelombang Aktif: <span class="text-primary-600 font-bold">{{ $wave->name }}</span></p>
    </div>

    <!-- Progress Steps -->
    <div class="relative mb-16 px-12">
        <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-100 -translate-y-1/2"></div>
        <div class="absolute top-1/2 left-0 h-0.5 bg-primary-500 -translate-y-1/2 transition-all duration-500" :style="`width: ${(step-1) * 50}%`"></div    >
        
        <div class="relative z-10 flex justify-between">
            <template x-for="s in steps" :key="s.id">
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center border-2 transition-all duration-500"
                        :class="step >= s.id ? 'bg-primary-600 border-primary-600 text-white shadow-xl shadow-primary-500/30' : 'bg-white border-slate-100 text-slate-300'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="s.icon"></path>
                        </svg>
                    </div>
                    <span class="mt-4 text-[10px] font-black uppercase tracking-[0.2em]" :class="step >= s.id ? 'text-primary-600' : 'text-slate-300'" x-text="s.title"></span>
                </div>
            </template>
        </div>
    </div>

    <form action="{{ route('tenant.ppdb.public.store') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="ppdb_wave_id" value="{{ $wave->id }}">

        <!-- Step 1: Personal Data -->
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="premium-card rounded-[3rem] p-12 space-y-10">
            <div class="space-y-1 border-l-4 border-primary-500 pl-6 rounded">
                <h3 class="text-2xl font-display font-extrabold text-slate-900">Data Diri Calon Siswa</h3>
                <p class="text-slate-400 text-sm font-medium">Lengkapi identitas asli sesuai dengan akta kelahiran.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Aditya Pratama" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Email (Aktif)</label>
                    <input type="email" name="email" placeholder="nama@email.com" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">No. WhatsApp</label>
                    <input type="text" name="phone" required placeholder="0812..." class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">NISN</label>
                    <input type="text" name="nisn" placeholder="10 Digit NISN" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Tempat Lahir</label>
                    <input type="text" name="pob" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Tanggal Lahir</label>
                    <input type="date" name="dob" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Alamat Domisili Lengkap</label>
                <textarea name="address" rows="3" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300"></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button type="button" @click="step = 2" class="px-10 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 hover:shadow-xl hover:shadow-primary-600/30 transition-all flex items-center gap-2 group">
                    Berikutnya: Data Keluarga
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>

        <!-- Step 2: Family Data -->
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="premium-card rounded-[3rem] p-12 space-y-10">
            <div class="space-y-1 border-l-4 border-accent-500 pl-6 rounded">
                <h3 class="text-2xl font-display font-extrabold text-slate-900">Data Orang Tua / Wali</h3>
                <p class="text-slate-400 text-sm font-medium">Informasi ini penting untuk korespondensi sekolah.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Nama Ayah</label>
                    <input type="text" name="father_name" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Nama Ibu</label>
                    <input type="text" name="mother_name" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button" @click="step = 1" class="px-10 py-4 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
                <button type="button" @click="step = 3" class="px-10 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 hover:shadow-xl hover:shadow-primary-600/30 transition-all flex items-center gap-2 group">
                    Ke Tahap Akhir
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>

        <!-- Step 3: Academic History -->
        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="premium-card rounded-[3rem] p-12 space-y-10">
            <div class="space-y-1 border-l-4 border-green-500 pl-6 rounded">
                <h3 class="text-2xl font-display font-extrabold text-slate-900">Riwayat Pendidikan</h3>
                <p class="text-slate-400 text-sm font-medium">Asal sekolah dan informasi akademik sebelumnya.</p>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Asal Sekolah (SD/SMP/Sederajat)</label>
                <input type="text" name="previous_school" placeholder="Nama Sekolah Sebelumnya" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium placeholder:text-slate-300">
            </div>

            @if($wave->major_id)
            <div class="space-y-2">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Jurusan yang Dituju</label>
                <div class="w-full px-6 py-4 bg-slate-100 border-2 border-slate-200 rounded-2xl flex items-center justify-between">
                    <span class="font-bold text-slate-700">{{ $wave->major->name ?? 'Jurusan Terpilih' }}</span>
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] uppercase font-black tracking-widest">Otomatis Terkunci</span>
                </div>
                <!-- Hidden Input to secure major_id -->
                <input type="hidden" name="major_id" value="{{ $wave->major_id }}">
                <p class="text-[10px] text-slate-400 mt-1 pl-1">Gelombang ini dikhususkan untuk calon siswa jurusan {{ $wave->major->name ?? 'ini' }}.</p>
            </div>
            @else
            <div class="space-y-2">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Pilihan Jurusan</label>
                <select name="major_id" required class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-primary-500 focus:bg-white focus:outline-none transition-all font-medium text-slate-700">
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ $major->is_full ? 'disabled' : '' }}>
                            {{ $major->name }} 
                            @if($major->capacity !== null)
                                ({{ $major->is_full ? 'PENUH' : 'Sisa ' . $major->remaining . ' kuota' }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="bg-primary-50 rounded-[2rem] p-8 border border-primary-100 flex gap-6 items-start">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary-600 shadow-sm flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-extrabold text-primary-900 leading-tight">Konfirmasi Pendaftaran</h4>
                    <p class="text-primary-700/70 text-sm leading-relaxed">Dengan mengklik 'Kirim Pendaftaran', Anda menyatakan bahwa data yang diisi telah benar. Petugas kami akan melakukan verifikasi berkas selanjutnya.</p>
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button" @click="step = 2" class="px-10 py-4 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
                <button type="submit" class="px-12 py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-600 hover:shadow-2xl hover:shadow-primary-600/30 transition-all">
                    KIRIM PENDAFTARAN
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
