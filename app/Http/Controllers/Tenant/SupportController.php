<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display a listing of support tickets
     */
    public function index(Request $request)
    {
        // Mock data for demonstration
        $tickets = collect([
            (object) [
                'id' => 'TICK-2024-001',
                'title' => 'Sistem tidak dapat diakses',
                'description' => 'Pengguna tidak dapat login ke sistem EduSaaS',
                'status' => 'open',
                'priority' => 'high',
                'category' => 'Technical Issue',
                'created_at' => '2024-03-16 09:00:00',
                'updated_at' => '2024-03-16 09:30:00',
                'requester' => 'Ahmad Rizki',
                'assigned_to' => 'Support Team',
            ],
            (object) [
                'id' => 'TICK-2024-002',
                'title' => 'Laporan keuangan tidak akurat',
                'description' => 'Total pembayaran di laporan tidak sesuai dengan transaksi',
                'status' => 'in_progress',
                'priority' => 'medium',
                'category' => 'Finance',
                'created_at' => '2024-03-15 14:20:00',
                'updated_at' => '2024-03-16 08:15:00',
                'requester' => 'Siti Nurhaliza',
                'assigned_to' => 'Finance Team',
            ],
            (object) [
                'id' => 'TICK-2024-003',
                'title' => 'Permintaan fitur baru',
                'description' => 'Mohon tambahkan fitur export data siswa ke Excel',
                'status' => 'pending',
                'priority' => 'low',
                'category' => 'Feature Request',
                'created_at' => '2024-03-14 11:45:00',
                'updated_at' => '2024-03-14 11:45:00',
                'requester' => 'Budi Santoso',
                'assigned_to' => null,
            ],
            (object) [
                'id' => 'TICK-2024-004',
                'title' => 'Integrasi WhatsApp gagal',
                'description' => 'Tidak dapat mengirim pesan WhatsApp melalui sistem',
                'status' => 'resolved',
                'priority' => 'high',
                'category' => 'Integration',
                'created_at' => '2024-03-13 16:30:00',
                'updated_at' => '2024-03-15 10:20:00',
                'requester' => 'Dewi Lestari',
                'assigned_to' => 'Integration Team',
            ],
        ]);

        // Filter tickets if requested
        if ($request->status) {
            $tickets = $tickets->where('status', $request->status);
        }

        if ($request->priority) {
            $tickets = $tickets->where('priority', $request->priority);
        }

        if ($request->category) {
            $tickets = $tickets->where('category', $request->category);
        }

        return view('tenant.support.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        return view('tenant.support.create');
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Technical Issue,Finance,Feature Request,Integration,Other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Here you would create the ticket in the database
        // For now, we'll just redirect with success message

        return redirect()->route('tenant.support.index')->with('success', 'Ticket berhasil dibuat. Tim support akan segera memproses permintaan Anda.');
    }

    /**
     * Display the specified ticket
     */
    public function show($id)
    {
        // Mock ticket data
        $ticket = (object) [
            'id' => $id,
            'title' => 'Sistem tidak dapat diakses',
            'description' => 'Pengguna tidak dapat login ke sistem EduSaaS setelah update terakhir',
            'status' => 'in_progress',
            'priority' => 'high',
            'category' => 'Technical Issue',
            'created_at' => '2024-03-16 09:00:00',
            'updated_at' => '2024-03-16 09:30:00',
            'requester' => 'Ahmad Rizki',
            'assigned_to' => 'Support Team',
            'messages' => [
                (object) [
                    'sender' => 'Ahmad Rizki',
                    'message' => 'Setelah update sistem kemarin, saya tidak dapat login ke akun saya. Sistem menampilkan error "Invalid credentials"',
                    'timestamp' => '2024-03-16 09:00:00',
                    'is_support' => false,
                ],
                (object) [
                    'sender' => 'Support Team',
                    'message' => 'Terima kasih atas laporannya. Kami sedang menyelidiki masalah ini. Mohon tunggu sebentar.',
                    'timestamp' => '2024-03-16 09:15:00',
                    'is_support' => true,
                ],
                (object) [
                    'sender' => 'Support Team',
                    'message' => 'Kami telah menemukan masalah pada sistem autentikasi. Tim teknis sedang memperbaikinya.',
                    'timestamp' => '2024-03-16 09:30:00',
                    'is_support' => true,
                ],
            ],
        ];

        return view('tenant.support.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket
     */
    public function edit($id)
    {
        // Mock ticket data
        $ticket = (object) [
            'id' => $id,
            'title' => 'Sistem tidak dapat diakses',
            'description' => 'Pengguna tidak dapat login ke sistem EduSaaS',
            'status' => 'open',
            'priority' => 'high',
            'category' => 'Technical Issue',
        ];

        return view('tenant.support.edit', compact('ticket'));
    }

    /**
     * Update the specified ticket
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Technical Issue,Finance,Feature Request,Integration,Other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Here you would update the ticket in the database

        return redirect()->route('tenant.support.show', $id)->with('success', 'Ticket berhasil diperbarui.');
    }

    /**
     * Add a message to the ticket
     */
    public function addMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Here you would add the message to the ticket

        return redirect()->route('tenant.support.show', $id)->with('success', 'Pesan berhasil dikirim.');
    }
}
