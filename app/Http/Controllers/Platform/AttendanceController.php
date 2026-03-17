<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use App\Models\Foundation;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display Attendance plugin overview
     */
    public function index(Request $request)
    {
        // Get or create the Attendance plugin
        $attendancePlugin = Plugin::where('name', 'Attendance System')->first();
        
        if (!$attendancePlugin) {
            // Create default attendance plugin if not exists
            $attendancePlugin = $this->createDefaultAttendancePlugin();
        }
        
        // Get installations
        $installations = PluginInstallation::with(['foundation'])
            ->where('plugin_id', $attendancePlugin->id)
            ->latest()
            ->paginate(20);
        
        // Statistics
        $stats = [
            'total_installations' => PluginInstallation::where('plugin_id', $attendancePlugin->id)->count(),
            'active_installations' => PluginInstallation::where('plugin_id', $attendancePlugin->id)->where('is_active', true)->count(),
            'foundations_with_plugin' => PluginInstallation::where('plugin_id', $attendancePlugin->id)->distinct('foundation_id')->count('foundation_id'),
            'today_installations' => PluginInstallation::where('plugin_id', $attendancePlugin->id)->whereDate('installed_at', today())->count(),
        ];
        
        // Get all foundations for installation
        $foundations = Foundation::pluck('name', 'id');
        
        return view('platform.attendance.index', compact('attendancePlugin', 'installations', 'stats', 'foundations'));
    }
    
    /**
     * Show Attendance plugin details
     */
    public function show(Plugin $plugin)
    {
        $plugin->load(['installations.foundation']);
        return view('platform.plugins.show', compact('plugin'));
    }
    
    /**
     * Install Attendance plugin for a foundation
     */
    public function install(Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);
        
        $attendancePlugin = Plugin::where('name', 'Attendance System')->first();
        
        if (!$attendancePlugin) {
            $attendancePlugin = $this->createDefaultAttendancePlugin();
        }
        
        $installation = PluginInstallation::firstOrCreate([
            'plugin_id' => $attendancePlugin->id,
            'foundation_id' => $request->foundation_id
        ], [
            'is_active' => true,
            'installed_at' => now(),
            'installed_by' => auth()->id()
        ]);
        
        if ($installation->wasRecentlyCreated) {
            $message = 'Plugin Absensi berhasil diinstal.';
        } else {
            $message = 'Plugin Absensi sudah terinstal.';
        }
        
        return redirect()->route('platform.attendance.index')
            ->with('success', $message);
    }
    
    /**
     * Uninstall Attendance plugin from a foundation
     */
    public function uninstall(Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);
        
        $attendancePlugin = Plugin::where('name', 'Attendance System')->first();
        
        if ($attendancePlugin) {
            $installation = PluginInstallation::where('plugin_id', $attendancePlugin->id)
                ->where('foundation_id', $request->foundation_id)
                ->first();
                
            if ($installation) {
                $installation->delete();
                return redirect()->route('platform.attendance.index')
                    ->with('success', 'Plugin Absensi berhasil dihapus dari yayasan.');
            }
        }
        
        return redirect()->route('platform.attendance.index')
            ->with('error', 'Instalasi tidak ditemukan.');
    }
    
    /**
     * Toggle plugin status for a foundation
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'installation_id' => 'required|exists:plugin_installations,id'
        ]);
        
        $installation = PluginInstallation::findOrFail($request->installation_id);
        $installation->update(['is_active' => !$installation->is_active]);
        
        $status = $installation->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('platform.attendance.index')
            ->with('success', "Plugin Absensi berhasil {$status}.");
    }
    
    /**
     * Create default Attendance plugin
     */
    private function createDefaultAttendancePlugin()
    {
        return Plugin::create([
            'name' => 'Attendance System',
            'description' => 'Sistem absensi lengkap dengan dukungan multiple metode otentikasi: QR Code, Fingerprint, Face Recognition, RFID, dan GPS Attendance. Fitur mencakup tracking kehadiran, laporan absensi, dan integrasi dengan sistem manajemen sekolah.',
            'version' => '1.0.0',
            'category' => 'management',
            'price' => 250000,
            'is_available_in_marketplace' => true,
            'status' => 'active',
            'features' => [
                'QR Code Attendance' => 'Scan QR codes untuk absensi dengan timer expiration',
                'Fingerprint Integration' => 'Verifikasi biometric sidik jari dengan anti-spoofing',
                'Face Recognition' => 'Deteksi wajah real-time dengan liveness detection',
                'RFID Card Support' => 'Baca kartu proximity dengan enkripsi data',
                'GPS Attendance' => 'Remote check-in dengan geofencing',
                'Reports & Analytics' => 'Laporan lengkap dengan export PDF/Excel/CSV',
                'Audit Logs' => 'Log semua eventos absensi',
                'RBAC' => 'Role-based access control',
                'Backup & Recovery' => 'Mekanisme backup data otomatis',
                'RESTful API' => 'Endpoint API untuk integrasi pihak ketiga'
            ],
            'author' => 'YayasanEdu',
            'support_email' => 'support@yayasanedu.id',
            'documentation_url' => 'https://docs.yayasanedu.id/attendance',
            'license_type' => 'premium',
            'is_featured' => true,
            'downloads' => 0,
            'rating' => 0,
            'installed_at' => now()
        ]);
    }
}
