<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index(Request $request): Response
    {
        // Contact information
        $contactInfo = [
            'email' => 'support@edusaas.com',
            'phone' => '(021) 1234-5678',
            'whatsapp' => '+6281234567890',
            'address' => 'Jl. Pendidikan No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'office_hours' => [
                'weekdays' => '08:00 - 17:00 WIB',
                'weekends' => '09:00 - 15:00 WIB',
                'timezone' => 'WIB (GMT+7)'
            ],
            'social_media' => [
                'facebook' => 'https://facebook.com/edusaas',
                'twitter' => 'https://twitter.com/edusaas',
                'linkedin' => 'https://linkedin.com/company/edusaas',
                'instagram' => 'https://instagram.com/edusaas'
            ]
        ];

        // FAQ data
        $faqs = [
            (object) [
                'question' => 'Apakah EduSaaS aman digunakan?',
                'answer' => 'Ya, EduSaaS menggunakan teknologi keamanan terkini dengan enkripsi data 256-bit, backup otomatis, dan compliance dengan standar keamanan internasional.',
                'category' => 'security'
            ],
            (object) [
                'question' => 'Berapa biaya langganan EduSaaS?',
                'answer' => 'Biaya langganan tergantung pada jumlah sekolah dan fitur yang digunakan. Hubungi tim sales kami untuk mendapatkan penawaran yang sesuai dengan kebutuhan yayasan Anda.',
                'category' => 'pricing'
            ],
            (object) [
                'question' => 'Apakah ada demo atau trial yang bisa dicoba?',
                'answer' => 'Ya, kami menyediakan demo gratis selama 14 hari dengan akses penuh ke semua fitur. Anda dapat mendaftar melalui website kami.',
                'category' => 'demo'
            ],
            (object) [
                'question' => 'Bagaimana cara migrasi data dari sistem lama?',
                'answer' => 'Tim teknis kami akan membantu proses migrasi data secara gratis. Kami mendukung berbagai format data dan memastikan proses berjalan lancar.',
                'category' => 'migration'
            ],
            (object) [
                'question' => 'Apakah ada pelatihan untuk pengguna baru?',
                'answer' => 'Ya, kami menyediakan pelatihan online dan offline untuk administrator sekolah. Dokumentasi lengkap juga tersedia di portal help center.',
                'category' => 'training'
            ],
            (object) [
                'question' => 'Apakah EduSaaS mendukung integrasi dengan sistem lain?',
                'answer' => 'Ya, EduSaaS mendukung integrasi dengan berbagai sistem seperti payment gateway, WhatsApp API, Google Workspace, dan sistem absensi.',
                'category' => 'integration'
            ],
        ];

        // Team members
        $teamMembers = [
            (object) [
                'name' => 'Dr. Ahmad Rahman',
                'position' => 'CEO & Founder',
                'email' => 'ahmad@edusaas.com',
                'phone' => '+6281234567890',
                'photo' => '/images/team/ahmad.jpg'
            ],
            (object) [
                'name' => 'Siti Nurhaliza',
                'position' => 'Head of Customer Success',
                'email' => 'siti@edusaas.com',
                'phone' => '+6281234567891',
                'photo' => '/images/team/siti.jpg'
            ],
            (object) [
                'name' => 'Budi Santoso',
                'position' => 'Technical Lead',
                'email' => 'budi@edusaas.com',
                'phone' => '+6281234567892',
                'photo' => '/images/team/budi.jpg'
            ],
        ];

        return response()->view('tenant.contacts.index', compact('contactInfo', 'faqs', 'teamMembers'));
    }

    /**
     * Store a contact message
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:general,technical,sales,support,billing',
            'message' => 'required|string|min:10|max:2000',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        // Here you would save the contact message to database
        // For now, we'll simulate sending an email

        try {
            // Simulate email sending
            // Mail::to('support@edusaas.com')->send(new ContactMessage($request->all()));

            $message = "Terima kasih atas pesan Anda, {$request->name}! Tim kami akan merespons dalam 24 jam.";

        } catch (\Exception $e) {
            $message = "Terjadi kesalahan saat mengirim pesan. Silakan coba lagi atau hubungi kami langsung.";
        }

        return response()->view('tenant.contacts.index', [
            'message' => $message,
            'message_type' => isset($e) ? 'error' : 'success'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()->view('tenant.contacts.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        return response()->view('tenant.contacts.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        return response()->view('tenant.contacts.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): Response
    {
        // Implementation for updating contact
        return response()->view('tenant.contacts.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        // Implementation for deleting contact
        return response()->view('tenant.contacts.index');
    }
}
