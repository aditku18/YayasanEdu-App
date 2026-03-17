@extends('layouts.dashboard')

@section('title', 'Buat Tagihan')

@section('content')
<div class="space-y-6 pb-12">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('tenant.school.finance.invoices.index') }}" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Buat Tagihan Baru</h1>
            <p class="text-slate-500">Buat tagihan untuk siswa</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('tenant.school.finance.invoices.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Siswa <span class="text-red-500">*</span></label>
                    <select name="student_id" id="studentSelect" required class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Siswa</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Tagihan <span class="text-red-500">*</span></label>
                        <select name="bill_type_id" id="billTypeSelect" required class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Jenis Tagihan</option>
                            @foreach($billTypes as $type)
                                <option value="{{ $type->id }}" data-amount="{{ $type->default_amount }}" data-type="{{ $type->type }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4" id="monthField" style="display: none;">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                        <input type="month" name="month" value="{{ now()->format('Y-m') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Tagihan <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" id="amount" required min="0" step="100" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Diskon</label>
                        <input type="number" name="discount" id="discount" min="0" step="100" value="0" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('tenant.school.finance.invoices.index') }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                        Simpan Tagihan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                <h3 class="font-semibold text-blue-900 mb-2">Informasi</h3>
                <p class="text-sm text-blue-700">
                    Buat tagihan individu untuk siswa. Untuk membuat tagihan massal (misal: SPP semua siswa), gunakan tombol "Generate Massal" di halaman daftar tagihan.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Preview</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jumlah Tagihan</span>
                        <span class="font-medium" id="previewAmount">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Diskon</span>
                        <span class="font-medium text-red-600" id="previewDiscount">Rp 0</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-semibold text-slate-900">Total</span>
                        <span class="font-bold text-primary-600" id="previewTotal">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const billTypeSelect = document.getElementById('billTypeSelect');
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount');
    const monthField = document.getElementById('monthField');

    billTypeSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const amount = option.dataset.amount || 0;
        const type = option.dataset.type;
        
        amountInput.value = amount;
        
        // Show/hide month field based on bill type
        if (type === 'monthly') {
            monthField.style.display = 'grid';
        } else {
            monthField.style.display = 'none';
        }
        
        updatePreview();
    });

    function updatePreview() {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const total = amount - discount;

        document.getElementById('previewAmount').textContent = 'Rp ' + amount.toLocaleString('id-ID');
        document.getElementById('previewDiscount').textContent = 'Rp ' + discount.toLocaleString('id-ID');
        document.getElementById('previewTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    amountInput.addEventListener('input', updatePreview);
    discountInput.addEventListener('input', updatePreview);
</script>
@endpush
@endsection
