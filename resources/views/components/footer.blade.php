<footer id="kontak" class="bg-slate-950 text-slate-400 py-12 border-t border-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8 border-b border-slate-800 pb-8">
            <div class="col-span-1">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center text-xs font-bold text-white">S</div>
                    <span class="text-white text-lg font-bold">SIS Platform</span>
                </a>
                <p class="text-sm">Sistem Informasi Sekolah Modern terbaik untuk Yayasan Pendidikan Indonesia.</p>
            </div>
            <div>
                <h5 class="text-white font-semibold mb-4">Tentang</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('landing') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-semibold mb-4">Produk</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="#fitur" class="hover:text-white transition">Fitur</a></li>
                    <li><a href="#harga" class="hover:text-white transition">Harga</a></li>
                    <li><a href="#faq" class="hover:text-white transition">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-semibold mb-4">Kontak</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="#kontak" class="hover:text-white transition">Hubungi Kami</a></li>
                </ul>
                <div class="flex items-center gap-3 mt-4">
                    <a href="#" class="text-slate-500 hover:text-white transition" aria-label="Twitter">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-white transition" aria-label="Facebook">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-white transition" aria-label="Instagram">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-white transition" aria-label="LinkedIn">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <p>&copy; {{ date('Y') }} SIS Platform. Hak Cipta Dilindungi.</p>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
