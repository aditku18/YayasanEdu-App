@extends('layouts.tenant-guest')

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #f8fafc; margin-bottom: 8px;">Verifikasi Email Anda</h2>
        <p style="font-size: 14px; color: #94a3b8; line-height: 1.5;">
            Terima kasih telah mendaftar! Sebelum memulai, dapatkah Anda memverifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan? 
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="margin-bottom: 20px; padding: 12px; border-radius: 8px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #4ade80; font-size: 14px; text-align: center;">
            Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 24px;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-login" style="width: 100%;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
            @csrf
            <button type="submit" style="background: none; border: none; font-size: 14px; color: #94a3b8; text-decoration: underline; cursor: pointer; transition: color 0.2s;"
                    onmouseover="this.style.color='#cbd5e1'"
                    onmouseout="this.style.color='#94a3b8'">
                Log Out
            </button>
        </form>
    </div>
@endsection
