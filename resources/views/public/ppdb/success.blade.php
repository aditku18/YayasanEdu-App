@extends('layouts.ppdb')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<style>
    @media print {
        body {
            background: white !important;
            -webkit-print-color-adjust: exact;
            margin: 0 !important;
            padding: 0 !important;
        }
        .no-print {
            display: none !important;
        }
        main {
            padding: 0 !important;
        }
        .print-only {
            display: block !important;
        }
        @page {
            size: A4;
            margin: 1.5cm 2cm; /* Slightly tighter vertical margin to prevent spillover */
        }
        .print-container {
            width: 100%;
            height: auto;
            min-height: auto;
            color: black;
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            page-break-after: avoid;
            page-break-inside: avoid;
            overflow: hidden;
        }
        .premium-card {
            border: none !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            background: white !important;
        }
    }
    .print-only {
        display: none;
    }
</style>

<div class="max-w-3xl mx-auto space-y-12">
    <!-- Web Interface (no-print) -->
    <div class="no-print space-y-12">
        <!-- Success Badge -->
        <div class="text-center space-y-6 pt-12">
            <div class="relative inline-block">
                <div class="w-32 h-32 bg-green-500 rounded-[3rem] flex items-center justify-center text-white shadow-2xl shadow-green-500/40 relative z-10 animate-bounce">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="absolute -top-4 -right-4 w-12 h-12 bg-amber-400 rounded-2xl flex items-center justify-center text-white shadow-lg animate-pulse">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
            </div>
            
            <div class="space-y-2">
                <h1 class="text-4xl font-display font-black text-slate-900">Pendaftaran Berhasil!</h1>
                <p class="text-slate-500 font-medium text-lg">Selamat, data Anda telah resmi terdaftar dalam sistem kami.</p>
            </div>
        </div>

        <!-- Registration Receipt Card (Web Version) -->
        <div class="premium-card rounded-[3rem] overflow-hidden border-2 border-primary-100">
            <div class="bg-primary-600 p-8 text-white flex justify-between items-center">
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Nomor Pendaftaran</p>
                    <p class="text-3xl font-display font-black tracking-tight">{{ $applicant->registration_number }}</p>
                </div>
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            
            <div class="p-10 space-y-8 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pendaftar</p>
                            <p class="font-bold text-slate-900">{{ $applicant->name }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gelombang</p>
                            <p class="font-bold text-slate-900">{{ $applicant->wave->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="py-6 border-t border-b border-dashed border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-xs font-bold text-slate-400">Total Uang Formulir Pendaftaran</p>
                        <p class="text-2xl font-black text-primary-600">Rp {{ number_format($applicant->wave->registration_fee, 0, ',', '.') }}</p>
                    </div>
                    <div class="px-5 py-2 bg-amber-50 rounded-full border border-amber-100 text-[10px] font-black text-amber-600 uppercase tracking-widest">
                        Menunggu Verifikasi Pembayaran
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary-500 rounded-full"></span>
                        Langkah Selanjutnya
                    </h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex gap-4 p-5 bg-slate-50 rounded-2xl items-start">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center font-bold text-slate-900 border border-slate-200 flex-shrink-0">1</div>
                            <div class="space-y-3">
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Lakukan pembayaran **Uang Formulir Pendaftaran** sesuai nominal di atas melalui transfer bank:</p>
                                <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-400 font-bold uppercase text-[10px]">Bank</span>
                                        <span class="font-black text-slate-900">Bank Syariah Indonesia (BSI)</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-400 font-bold uppercase text-[10px]">No. Rekening</span>
                                        <span class="font-black text-primary-600">7722 0000 1234</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-400 font-bold uppercase text-[10px]">Atas Nama</span>
                                        <span class="font-black text-slate-900">YAYASAN EDU INDONESIA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="flex gap-4 items-start">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center font-bold text-slate-900 border border-slate-200 flex-shrink-0 text-sm">2</div>
                                <div class="space-y-3">
                                    <p class="text-sm text-slate-900 font-black uppercase tracking-widest">Lengkapi Berkas Persyaratan</p>
                                    <ul class="text-xs text-slate-600 space-y-2 ml-1">
                                        <li class="flex gap-2"><span>•</span> <span>Klik menu <strong>"Cek Status"</strong> di bagian navigasi atas.</span></li>
                                        <li class="flex gap-2"><span>•</span> <span>Masukkan <strong>Nomor Pendaftaran</strong> & <strong>No. WhatsApp</strong> Anda.</span></li>
                                        <li class="flex gap-2"><span>•</span> <span>Pilih file pindaian (Scan) <strong>KK, Akta, Ijazah, & Pas Foto</strong>.</span></li>
                                        <li class="flex gap-2"><span>•</span> <span class="text-amber-600 font-bold italic">Wajib:</span> <span>Unggah juga <strong>Bukti Transfer / Struk Pembayaran</strong>.</span></li>
                                        <li class="flex gap-2"><span>•</span> <span>Klik <strong>"Unggah Seluruh Berkas"</strong> untuk memproses verifikasi.</span></li>
                                    </ul>
                                    <div class="pt-2">
                                        <a href="{{ route('tenant.ppdb.public.check-status') }}" class="inline-flex items-center gap-2 text-xs font-black text-primary-600 hover:text-primary-700">
                                            Buka Halaman Cek Status & Unggah
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 p-5 bg-blue-50 rounded-2xl items-start border border-blue-100">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center font-bold text-blue-600 border border-blue-200 flex-shrink-0">!</div>
                            <p class="text-sm text-blue-700 leading-relaxed font-bold">Catatan: Biaya Pendidikan (SPP, Uang Pangkal, dll) hanya dibayarkan setelah calon siswa dinyatakan **DITERIMA** melalui hasil seleksi.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-8 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-4 justify-center">
                <button onclick="window.print()" class="px-8 py-3 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4"/></svg>
                    Cetak Bukti Pendaftaran
                </button>
                <a href="{{ route('tenant.ppdb.public.index') }}" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        
        <p class="text-center text-slate-400 text-xs font-medium italic">Simpan Nomor Pendaftaran Anda dengan baik untuk keperluan verifikasi di masa mendatang.</p>
    </div>

    <!-- Print Only Template (A4 Professional) -->
    <div class="print-only print-container">
        <div class="space-y-4">
            <!-- Official Header -->
            <div class="flex items-center gap-6 border-b-4 border-black pb-3 mb-1">
                <div class="w-20 h-20 border-2 border-black flex items-center justify-center p-1 bg-white font-black text-center text-[10px] leading-tight flex-shrink-0">
                    LOGO<br>LEMBAGA
                </div>
                <div class="flex-1 text-center pr-20">
                    <h2 class="text-2xl font-black uppercase tracking-widest leading-none">Yayasan Pendidikan Edu Indonesia</h2>
                    <h3 class="text-xl font-bold uppercase mt-1">Sistem Informasi Sekolah Terpadu (SIS EDU)</h3>
                    <p class="text-[10px] font-medium mt-1">Sekretariat Utama: Jl. Pendidikan Raya No. 123, Central District, Kota Pendidikan, 15432</p>
                    <p class="text-[10px] font-medium underline">Telp: (021) 555-0123 | Email: support@sis-edu.sch.id | Web: www.sis-edu.sch.id</p>
                </div>
            </div>

            <div class="text-center py-1 relative">
                <h1 class="text-xl font-black underline uppercase tracking-[0.2em]">Kartu Bukti Pendaftaran PPDB Online</h1>
                <p class="text-xs font-bold text-gray-800 mt-1">Tahun Pelajaran 2025 / 2026</p>
                
                <!-- Registration Number Highlight -->
                <div class="absolute right-0 top-0 border-2 border-black p-2 text-center min-w-[120px]">
                    <p class="text-[8px] font-black uppercase tracking-tighter">No. Peserta</p>
                    <p class="text-lg font-black">{{ $applicant->registration_number }}</p>
                </div>
            </div>

            <!-- Main Data Table -->
            <div class="flex gap-4 items-start">
                <div class="flex-1">
                    <table class="w-full text-[11px] border-collapse">
                        <!-- BIODATA SECTION -->
                        <tr class="bg-gray-100 border border-black">
                            <td colspan="3" class="px-2 py-1 font-black uppercase tracking-widest text-[9px]">I. Identitas Calon Siswa</td>
                        </tr>
                        <tr class="border-x border-black">
                            <td class="w-1/3 p-2 font-bold">Nama Lengkap</td>
                            <td class="w-2 p-2">:</td>
                            <td class="p-2 font-bold uppercase">{{ $applicant->name }}</td>
                        </tr>
                        <tr class="border-x border-black border-t border-gray-200">
                            <td class="p-2 font-bold">NISN / NIK</td>
                            <td class="p-2">:</td>
                            <td class="p-2 font-mono">{{ $applicant->nisn ?? '-' }} / {{ $applicant->nik ?? '-' }}</td>
                        </tr>
                        <tr class="border-x border-black border-t border-gray-200">
                            <td class="p-2 font-bold">Tempat, Tanggal Lahir</td>
                            <td class="p-2">:</td>
                            <td class="p-2">{{ $applicant->pob ?? '-' }}, {{ $applicant->dob ? date('d F Y', strtotime($applicant->dob)) : '-' }}</td>
                        </tr>
                        <tr class="border-x border-black border-t border-gray-200">
                            <td class="p-2 font-bold">Alamat Domisili</td>
                            <td class="p-2">:</td>
                            <td class="p-2 leading-tight">{{ $applicant->address ?? '-' }}</td>
                        </tr>

                        <!-- FAMILY SECTION -->
                        <tr class="bg-gray-100 border border-black">
                            <td colspan="3" class="px-2 py-1 font-black uppercase tracking-widest text-[9px]">II. Data Orang Tua / Wali</td>
                        </tr>
                        <tr class="border-x border-black">
                            <td class="p-2 font-bold">Nama Ayah / Ibu</td>
                            <td class="p-2">:</td>
                            <td class="p-2">{{ $applicant->father_name ?? '-' }} / {{ $applicant->mother_name ?? '-' }}</td>
                        </tr>
                        <tr class="border-x border-black border-t border-gray-200">
                            <td class="p-2 font-bold">Handphone / WA</td>
                            <td class="p-2">:</td>
                            <td class="p-2">{{ $applicant->phone ?? '-' }}</td>
                        </tr>

                        <!-- ACADEMIC SECTION -->
                        <tr class="bg-gray-100 border border-black">
                            <td colspan="3" class="px-2 py-1 font-black uppercase tracking-widest text-[9px]">III. Informasi Pendaftaran</td>
                        </tr>
                        <tr class="border-x border-black border-b border-gray-200">
                            <td class="p-1.5 font-bold">Gelombang</td>
                            <td class="p-1.5">:</td>
                            <td class="p-1.5">{{ $applicant->wave->name }}</td>
                        </tr>
                        <tr class="border-x border-black border-b border-gray-200">
                            <td class="p-1.5 font-bold">Biaya Formulir</td>
                            <td class="p-1.5">:</td>
                            <td class="p-1.5">Rp {{ number_format($applicant->wave->registration_fee ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-x border-black border-b border-black bg-slate-50">
                            <td class="p-1.5 font-bold italic">Total Uang Formulir</td>
                            <td class="p-1.5">:</td>
                            <td class="p-1.5 font-black italic text-sm">Rp {{ number_format($applicant->wave->registration_fee, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-x border-black border-b border-black">
                            <td colspan="3" class="p-2 text-[9px] font-bold text-gray-600 italic leading-tight">
                                * Catatan: Biaya pendaftaran/pendidikan lainnya dibayar setelah dinyatakan diterima.
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- PAS FOTO SPACE -->
                <div class="w-[3cm] h-[4cm] border-2 border-black border-dashed flex items-center justify-center relative flex-shrink-0 bg-gray-50">
                    <div class="text-center text-[9px] font-bold text-gray-400 space-y-1">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <p>PAS FOTO<br>3 X 4</p>
                    </div>
                    <!-- Official Border Accent -->
                    <div class="absolute -top-1 -left-1 w-3 h-3 border-t-2 border-l-2 border-black"></div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 border-b-2 border-r-2 border-black"></div>
                </div>
            </div>

            <!-- Verification Status Banner -->
            <div class="border-2 border-black p-4 bg-gray-50 flex items-center gap-6">
                <div class="flex-shrink-0 text-center border-r-2 border-black pr-6">
                    <p class="text-[8px] font-black uppercase tracking-tighter">Status Validasi</p>
                    <p class="text-sm font-black text-primary-700">TERDAFTAR</p>
                </div>
                <div class="flex-1 space-y-2">
                    <p class="text-[9px] font-medium italic text-gray-600 leading-tight">
                        Dokumen ini adalah bukti sah bahwa calon siswa telah melakukan pendaftaran online. Silakan lakukan pembayaran **Uang Formulir** via transfer ke:
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-[9px] border-t border-black pt-1">
                        <p><span class="font-bold">Bank:</span> BSI (Bank Syariah Indonesia)</p>
                        <p><span class="font-bold">No. Rek:</span> 7722 0000 1234</p>
                        <p class="col-span-2 font-bold uppercase">A.N. YAYASAN PENDIDIKAN EDU INDONESIA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Official Signatures & QR -->
        <div class="flex justify-between items-end pb-4 pt-8">
            <div class="text-center space-y-2">
                <div class="w-28 h-28 border-2 border-black p-1 bg-white mx-auto relative overflow-hidden">
                    <div class="bg-black w-full h-full p-2 flex items-center justify-center text-white flex-col space-y-1">
                        <span class="text-[10px] font-black tracking-tighter">SECURITY VALID</span>
                        <div class="w-10 h-10 border border-white/50 flex items-center justify-center">
                            <div class="w-4 h-4 bg-white animate-pulse"></div>
                        </div>
                        <span class="text-[7px] opacity-70 font-mono">{{ substr(md5($applicant->registration_number), 0, 16) }}</span>
                    </div>
                </div>
                <p class="text-[8px] font-black uppercase text-gray-500">Otentikasi Digital</p>
            </div>

            <div class="text-center w-72 space-y-10 relative">
                <!-- Seal/Stempel Placeholder -->
                <div class="absolute -top-4 left-0 w-20 h-20 border-4 border-primary-600/20 rounded-full flex items-center justify-center -rotate-12 pointer-events-none">
                    <span class="text-[8px] font-black text-primary-600/20 uppercase text-center leading-none">PANITIA PPDB<br>SIS EDU</span>
                </div>
                
                <p class="text-xs font-bold leading-tight">Panitia PPDB,<br>{{ date('d F Y') }}</p>
                <div class="space-y-0.5">
                    <p class="text-base font-black uppercase underline decoration-2 underline-offset-4 tracking-tight">Ketua Panitia PPDB</p>
                    <p class="text-[10px] font-bold text-gray-600 tracking-widest uppercase">ID Petugas: {{ mt_rand(1000, 9999) }} / SEC / 2025</p>
                </div>
            </div>
        </div>

        <div class="border-t-2 border-black pt-4 flex justify-between items-center text-[8px] font-black uppercase tracking-[0.2em] text-gray-400">
            <span>SIS EDU - Enterprise Global Portal</span>
            <span>Halaman 1 dari 1</span>
            <span>Ver. 2025.1.0</span>
        </div>
    </div>
</div>
@endsection
