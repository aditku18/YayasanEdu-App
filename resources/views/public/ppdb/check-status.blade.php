@extends('layouts.ppdb')

@section('title', 'Cek Status Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <div class="premium-card rounded-[3rem] overflow-hidden">
        <div class="bg-primary-600 p-10 text-white text-center space-y-4">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto border border-white/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="space-y-2">
                <h1 class="text-3xl font-display font-black tracking-tight">Pelacak Pendaftaran</h1>
                <p class="text-primary-100 font-medium opacity-80">Masukkan informasi pendaftaran Anda untuk melanjutkan.</p>
            </div>
        </div>

        <div class="p-12 bg-white">
            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('tenant.ppdb.public.tracking') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Pendaftaran</label>
                        <input type="text" name="registration_number" required placeholder="Contoh: PPDB-20250001" 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" required placeholder="Sesuai saat pendaftaran" 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 hover:shadow-2xl hover:shadow-primary-600/30 transition-all flex items-center justify-center gap-3">
                    Lacak Status & Lengkapi Berkas
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>

        <div class="p-8 bg-slate-50 border-t border-slate-100 text-center">
            <p class="text-xs font-bold text-slate-400">Lupa nomor pendaftaran? Hubungi Panitia via <a href="#" class="text-primary-600 hover:underline">WhatsApp Center</a></p>
        </div>
    </div>
</div>
@endsection
