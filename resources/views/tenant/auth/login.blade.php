@extends('layouts.tenant-guest')

@section('content')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: #cbd5e1; margin-bottom: 8px;">Email Address</label>
            <div style="position: relative;">
                <div style="position: absolute; inset-y: 0; left: 0; padding-left: 16px; display: flex; align-items: center; pointer-events: none;">
                    <svg style="width: 20px; height: 20px; color: rgba(255, 255, 255, 0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="form-input"
                    placeholder="email@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" style="margin-top: 8px;" />
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: #cbd5e1; margin-bottom: 8px;">Password</label>
            <div style="position: relative;">
                <div style="position: absolute; inset-y: 0; left: 0; padding-left: 16px; display: flex; align-items: center; pointer-events: none;">
                    <svg style="width: 20px; height: 20px; color: rgba(255, 255, 255, 0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="form-input"
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" style="margin-top: 8px;" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <label for="remember_me" style="display: inline-flex; align-items: center; cursor-pointer;">
                <input id="remember_me" type="checkbox" name="remember"
                    style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.05); color: #3b82f6;">
                <span style="margin-left: 8px; font-size: 14px; color: #94a3b8;">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a style="font-size: 14px; color: #60a5fa; text-decoration: none; transition: color 0.2s;" 
                   href="{{ route('password.request') }}"
                   onmouseover="this.style.color='#93c5fd'"
                   onmouseout="this.style.color='#60a5fa'">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div style="margin-bottom: 28px;">
            <button type="submit" class="btn-login">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk ke Dashboard
            </button>
        </div>

        <!-- Back to Central -->
        <div style="text-align: center;">
            <a href="http://127.0.0.1:8001" 
               style="font-size: 14px; color: #94a3b8; text-decoration: none; transition: color 0.2s;"
               onmouseover="this.style.color='#cbd5e1'"
               onmouseout="this.style.color='#94a3b8'">
                ← Kembali ke Platform Central
            </a>
        </div>
    </form>
@endsection
