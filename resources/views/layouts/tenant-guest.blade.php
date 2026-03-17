<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduSaaS') }} — Login Yayasan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #1e293b 0%, #334155 40%, #475569 70%, #64748b 100%);
                min-height: 100vh;
                margin: 0;
                padding: 0;
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 16px;
                padding: 32px 40px;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            
            @keyframes slide-up {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .animate-slide-up {
                animation: slide-up 0.5s ease-out;
            }
            
            @keyframes fade-in {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            .animate-fade-in {
                animation: fade-in 0.5s ease-out;
            }
            
            .form-input {
                width: 100%;
                padding: 12px 16px 12px 48px;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                color: white;
                placeholder-color: rgba(255, 255, 255, 0.4);
                font-size: 14px;
                transition: all 0.2s;
            }
            
            .form-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            }
            
            .btn-login {
                width: 100%;
                padding: 12px 16px;
                background: linear-gradient(to right, #2563eb, #4f46e5);
                color: white;
                font-weight: 600;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .btn-login:hover {
                background: linear-gradient(to right, #1d4ed8, #4338ca);
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <body>
        <div style="min-height: 100vh; display: flex; position: relative; overflow: hidden;">

            <!-- Decorative floating orbs -->
            <div style="position: absolute; top: 80px; left: 40px; width: 288px; height: 288px; background: rgba(59, 130, 246, 0.2); border-radius: 50%; filter: blur(48px); animation: float 6s ease-in-out infinite;"></div>
            <div style="position: absolute; bottom: 80px; right: 40px; width: 384px; height: 384px; background: rgba(99, 102, 241, 0.15); border-radius: 50%; filter: blur(48px); animation: float 6s ease-in-out infinite; animation-delay: -3s;"></div>
            <div style="position: absolute; top: 50%; left: 33%; width: 256px; height: 256px; background: rgba(148, 163, 184, 0.1); border-radius: 50%; filter: blur(48px); animation: float 6s ease-in-out infinite; animation-delay: -1.5s;"></div>

            <!-- Left side — Foundation Info -->
            <div style="display: none; @media (min-width: 1024px) { display: flex; } width: 50%; align-items: center; justify-content: center; padding: 48px; position: relative; z-index: 10;">
                <div style="max-width: 448px; animation: fade-in 0.5s ease-out;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 32px;">
                        <div style="width: 56px; height: 56px; background: linear-gradient(to bottom right, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h1 style="font-size: 24px; font-weight: 700; color: white; letter-spacing: -0.025em;">Portal Yayasan</h1>
                            <p style="color: #93c5fd; font-size: 14px; font-weight: 500;">{{ tenant('id') ?? 'Tenant' }}</p>
                        </div>
                    </div>
                    
                    <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 16px; padding: 24px; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <h2 style="font-size: 20px; font-weight: 700; color: white; margin-bottom: 8px;">Dashboard Yayasan</h2>
                        <p style="color: #cbd5e1; font-size: 14px; margin-bottom: 16px;">Kelola data sekolah dan siswa dengan sistem terintegrasi</p>
                        
                        <div style="background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(96, 165, 250, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <svg style="width: 16px; height: 16px; color: #60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span style="color: #93c5fd; font-weight: 500; font-size: 14px;">Masa Trial Aktif</span>
                            </div>
                            <p style="color: #bfdbfe; font-size: 12px;">Nikmati full features selama masa trial</p>
                        </div>
                    </div>

                    <div style="margin-top: 32px; display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px; color: #cbd5e1;">
                            <div style="width: 32px; height: 32px; background: rgba(34, 197, 94, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 16px; height: 16px; color: #4ade80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span style="font-size: 14px;">Manajemen data siswa & guru</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; color: #cbd5e1;">
                            <div style="width: 32px; height: 32px; background: rgba(59, 130, 246, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 16px; height: 16px; color: #60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span style="font-size: 14px;">Laporan & analytics real-time</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; color: #cbd5e1;">
                            <div style="width: 32px; height: 32px; background: rgba(168, 85, 247, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 16px; height: 16px; color: #a78bfa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span style="font-size: 14px;">Keamanan data terjamin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side — Login form -->
            <div style="width: 100%; @media (min-width: 1024px) { width: 50%; } display: flex; align-items: center; justify-content: center; padding: 24px 48px; position: relative; z-index: 10;">
                <div style="width: 100%; max-width: 448px; animation: slide-up 0.5s ease-out;">
                    <!-- Mobile branding -->
                    <div style="display: flex; @media (min-width: 1024px) { display: none; } align-items: center; gap: 12px; margin-bottom: 32px; justify-content: center;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(to bottom right, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span style="font-size: 20px; font-weight: 700; color: white;">Portal Yayasan</span>
                    </div>

                    <div class="glass-card">
                        <div style="margin-bottom: 32px;">
                            <h2 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;">Selamat Datang</h2>
                            <p style="color: #94a3b8; font-size: 14px;">Masuk ke dashboard yayasan Anda</p>
                        </div>

                        @yield('content')
                    </div>

                    <p style="text-align: center; color: #64748b; font-size: 12px; margin-top: 24px;">
                        &copy; {{ date('Y') }} EduSaaS. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
