<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class DocumentationController extends Controller
{
    /**
     * Display a listing of documentation
     */
    public function index(Request $request): Response
    {
        // Mock documentation data
        $categories = [
            (object) [
                'id' => 'getting-started',
                'name' => 'Memulai',
                'description' => 'Panduan untuk memulai menggunakan EduSaaS',
                'icon' => '🚀',
                'article_count' => 8,
                'color' => 'emerald',
                'articles' => [
                    (object) ['id' => 'quick-start', 'title' => 'Panduan Cepat Memulai', 'read_time' => '10 min'],
                    (object) ['id' => 'setup-account', 'title' => 'Setup Akun Yayasan', 'read_time' => '5 min'],
                    (object) ['id' => 'add-schools', 'title' => 'Menambah Sekolah', 'read_time' => '8 min'],
                ]
            ],
            (object) [
                'id' => 'user-management',
                'name' => 'Manajemen Pengguna',
                'description' => 'Kelola pengguna, role, dan permission',
                'icon' => '👥',
                'article_count' => 12,
                'color' => 'blue',
                'articles' => [
                    (object) ['id' => 'user-roles', 'title' => 'Memahami Role dan Permission', 'read_time' => '8 min'],
                    (object) ['id' => 'add-users', 'title' => 'Menambah Pengguna', 'read_time' => '6 min'],
                    (object) ['id' => 'manage-roles', 'title' => 'Mengelola Role', 'read_time' => '12 min'],
                ]
            ],
            (object) [
                'id' => 'finance',
                'name' => 'Keuangan',
                'description' => 'Sistem tagihan, pembayaran, dan laporan keuangan',
                'icon' => '💰',
                'article_count' => 15,
                'color' => 'green',
                'articles' => [
                    (object) ['id' => 'payment-setup', 'title' => 'Konfigurasi Sistem Pembayaran', 'read_time' => '12 min'],
                    (object) ['id' => 'manage-bills', 'title' => 'Mengelola Tagihan', 'read_time' => '10 min'],
                    (object) ['id' => 'financial-reports', 'title' => 'Laporan Keuangan', 'read_time' => '15 min'],
                ]
            ],
            (object) [
                'id' => 'integrations',
                'name' => 'Integrasi',
                'description' => 'API, webhook, dan integrasi eksternal',
                'icon' => '🔗',
                'article_count' => 10,
                'color' => 'purple',
                'articles' => [
                    (object) ['id' => 'api-basics', 'title' => 'Dasar-dasar API', 'read_time' => '8 min'],
                    (object) ['id' => 'webhooks', 'title' => 'Konfigurasi Webhook', 'read_time' => '6 min'],
                    (object) ['id' => 'third-party', 'title' => 'Integrasi Pihak Ketiga', 'read_time' => '14 min'],
                ]
            ],
            (object) [
                'id' => 'troubleshooting',
                'name' => 'Penyelesaian Masalah',
                'description' => 'Solusi untuk masalah umum',
                'icon' => '🔧',
                'article_count' => 6,
                'color' => 'amber',
                'articles' => [
                    (object) ['id' => 'common-issues', 'title' => 'Masalah Umum', 'read_time' => '5 min'],
                    (object) ['id' => 'login-problems', 'title' => 'Masalah Login', 'read_time' => '4 min'],
                    (object) ['id' => 'payment-errors', 'title' => 'Error Pembayaran', 'read_time' => '6 min'],
                ]
            ],
            (object) [
                'id' => 'api-reference',
                'name' => 'Referensi API',
                'description' => 'Dokumentasi lengkap API EduSaaS',
                'icon' => '📚',
                'article_count' => 25,
                'color' => 'indigo',
                'articles' => [
                    (object) ['id' => 'authentication', 'title' => 'Autentikasi API', 'read_time' => '8 min'],
                    (object) ['id' => 'endpoints', 'title' => 'Daftar Endpoint', 'read_time' => '15 min'],
                    (object) ['id' => 'rate-limits', 'title' => 'Batas Rate dan Limit', 'read_time' => '6 min'],
                ]
            ],
        ];

        $featuredArticles = [
            (object) [
                'id' => 'quick-start',
                'title' => 'Panduan Cepat Memulai',
                'description' => 'Pelajari dasar-dasar penggunaan EduSaaS dalam 10 menit',
                'category' => 'Memulai',
                'read_time' => '10 min read',
                'updated_at' => '2024-03-15',
            ],
            (object) [
                'id' => 'user-roles',
                'title' => 'Memahami Role dan Permission',
                'description' => 'Panduan lengkap tentang sistem role dan permission',
                'category' => 'Manajemen Pengguna',
                'read_time' => '8 min read',
                'updated_at' => '2024-03-14',
            ],
            (object) [
                'id' => 'payment-setup',
                'title' => 'Konfigurasi Sistem Pembayaran',
                'description' => 'Setup gateway pembayaran dan konfigurasi billing',
                'category' => 'Keuangan',
                'read_time' => '12 min read',
                'updated_at' => '2024-03-13',
            ],
        ];

        return response()->view('tenant.documentation.index', compact('categories', 'featuredArticles'));
    }

    /**
     * Display the specified documentation article
     */
    public function show(string $category, string $article = null)
    {
        // Mock article data based on category and article
        $articles = [
            'getting-started' => [
                'quick-start' => [
                    'title' => 'Panduan Cepat Memulai EduSaaS',
                    'content' => '
                        <h2>Selamat Datang di EduSaaS</h2>
                        <p>EduSaaS adalah platform manajemen sekolah berbasis web yang lengkap untuk yayasan pendidikan.</p>

                        <h3>Langkah 1: Setup Akun Yayasan</h3>
                        <p>Pertama, Anda perlu membuat akun yayasan dan mengkonfigurasi informasi dasar sekolah.</p>

                        <h3>Langkah 2: Tambah Sekolah</h3>
                        <p>Setelah akun yayasan siap, tambahkan sekolah-sekolah yang akan dikelola dalam sistem.</p>

                        <h3>Langkah 3: Konfigurasi Sistem</h3>
                        <p>Setup sistem pembayaran, integrasi, dan pengaturan lainnya sesuai kebutuhan.</p>

                        <h3>Langkah 4: Mulai Menggunakan</h3>
                        <p>Sistem siap digunakan untuk manajemen sekolah harian.</p>
                    ',
                ]
            ],
            'user-management' => [
                'user-roles' => [
                    'title' => 'Memahami Role dan Permission',
                    'content' => '
                        <h2>Sistem Role dan Permission</h2>
                        <p>EduSaaS menggunakan sistem role-based access control (RBAC) untuk mengelola akses pengguna.</p>

                        <h3>Jenis Role</h3>
                        <ul>
                            <li><strong>Yayasan Admin:</strong> Akses penuh ke semua fitur</li>
                            <li><strong>Sekolah Admin:</strong> Akses ke sekolah tertentu</li>
                            <li><strong>Guru:</strong> Akses ke fitur akademik</li>
                            <li><strong>Siswa:</strong> Akses terbatas ke data pribadi</li>
                        </ul>

                        <h3>Mengelola Permission</h3>
                        <p>Setiap role memiliki permission spesifik yang dapat dikustomisasi.</p>
                    ',
                ]
            ],
            'finance' => [
                'payment-setup' => [
                    'title' => 'Konfigurasi Sistem Pembayaran',
                    'content' => '
                        <h2>Setup Sistem Pembayaran</h2>
                        <p>Konfigurasi gateway pembayaran untuk menerima pembayaran dari siswa dan orang tua.</p>

                        <h3>Gateway yang Didukung</h3>
                        <ul>
                            <li>Midtrans</li>
                            <li>Xendit</li>
                            <li>DOKU</li>
                            <li>PayPal</li>
                            <li>Stripe</li>
                        </ul>

                        <h3>Konfigurasi</h3>
                        <ol>
                            <li>Masuk ke menu Payment Gateway</li>
                            <li>Pilih provider yang diinginkan</li>
                            <li>Masukkan API key dan secret</li>
                            <li>Test koneksi</li>
                            <li>Aktifkan gateway</li>
                        </ol>
                    ',
                ]
            ]
        ];

        // Default article if not specified
        if (!$article) {
            $article = 'quick-start';
        }

        $articleData = $articles[$category][$article] ?? $articles['getting-started']['quick-start'];

        $article = (object) [
            'id' => $article,
            'title' => $articleData['title'],
            'category' => ucfirst(str_replace('-', ' ', $category)),
            'content' => $articleData['content'],
            'read_time' => '10 min read',
            'updated_at' => '2024-03-15',
            'author' => 'Tim EduSaaS',
            'tags' => ['tutorial', 'documentation'],
        ];

        return response()->view('tenant.documentation.show', compact('article', 'category'));
    }

    /**
     * Search documentation
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        // Mock search results
        $results = collect([
            (object) [
                'id' => 'quick-start',
                'title' => 'Panduan Cepat Memulai',
                'category' => 'Memulai',
                'excerpt' => 'Pelajari dasar-dasar penggunaan EduSaaS dalam 10 menit...',
            ],
            (object) [
                'id' => 'user-roles',
                'title' => 'Memahami Role dan Permission',
                'category' => 'Manajemen Pengguna',
                'excerpt' => 'Panduan lengkap tentang sistem role dan permission...',
            ],
        ]);

        return response()->view('tenant.documentation.search', compact('results', 'query'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create(): Response
    {
        return response()->view('tenant.documentation.create');
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request): Response
    {
        // Implementation for storing documentation
        return response()->view('tenant.documentation.index');
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(string $id): Response
    {
        return response()->view('tenant.documentation.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, string $id): Response
    {
        // Implementation for updating documentation
        return response()->view('tenant.documentation.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(string $id): Response
    {
        // Implementation for deleting documentation
        return response()->view('tenant.documentation.index');
    }
}
