<?php

namespace App\Plugins\PPDB\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\PPDB\Services\PPDBService;
use App\Plugins\PPDB\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $ppdbService;
    protected $paymentService;

    public function __construct(PPDBService $ppdbService, PaymentService $paymentService)
    {
        $this->ppdbService = $ppdbService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display PPDB dashboard
     */
    public function dashboard(Request $request)
    {
        try {
            $stats = $this->ppdbService->getDashboardStats();
            
            return view('ppdb::admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            \Log::error('PPDB Dashboard Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    /**
     * Display applicants list
     */
    public function applicants(Request $request)
    {
        try {
            $query = \App\Models\PPDBApplicant::query()->with(['wave', 'major', 'school']);

            // Apply foundation/school filter
            if (auth()->user()->role !== 'foundation_admin') {
                $query->where('school_unit_id', auth()->user()->school_unit_id);
            } elseif ($request->school_unit_id) {
                $query->where('school_unit_id', $request->school_unit_id);
            }

            // Apply filters
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('registration_number', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->ppdb_wave_id) {
                $query->where('ppdb_wave_id', $request->ppdb_wave_id);
            }

            if ($request->major_id) {
                $query->where('major_id', $request->major_id);
            }

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $applicants = $query->latest()->paginate(20);
            $waves = \App\Models\PPDBWave::where('status', 'active')->pluck('name', 'id');
            $majors = \App\Models\Major::pluck('name', 'id');
            $schools = \App\Models\SchoolUnit::pluck('name', 'id');

            return view('ppdb::admin.applicants.index', compact(
                'applicants', 'waves', 'majors', 'schools'
            ));
        } catch (\Exception $e) {
            \Log::error('PPDB Applicants Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data pendaftar.');
        }
    }

    /**
     * Show applicant details
     */
    public function showApplicant($id)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::with([
                'wave', 'major', 'school', 'wave.fees'
            ])->findOrFail($id);

            // Check access
            if (auth()->user()->role !== 'foundation_admin' && 
                $applicant->school_unit_id !== auth()->user()->school_unit_id) {
                abort(403);
            }

            return view('ppdb::admin.applicants.show', compact('applicant'));
        } catch (\Exception $e) {
            \Log::error('PPDB Show Applicant Error: ' . $e->getMessage());
            
            return redirect()->route('ppdb.admin.applicants.index')
                ->with('error', 'Data pendaftar tidak ditemukan.');
        }
    }

    /**
     * Verify applicant
     */
    public function verifyApplicant(Request $request, $id)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::findOrFail($id);

            // Check access
            if (auth()->user()->role !== 'foundation_admin' && 
                $applicant->school_unit_id !== auth()->user()->school_unit_id) {
                abort(403);
            }

            if ($applicant->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Status pendaftar tidak memungkinkan verifikasi.');
            }

            $applicant->status = 'verified';
            $applicant->verified_at = now();
            $applicant->verified_by = auth()->id();
            $applicant->save();

            // Send notification
            $this->sendStatusNotification($applicant, 'verified');

            // Clear cache
            $this->ppdbService->clearCache();

            return redirect()->back()
                ->with('success', 'Pendaftar berhasil diverifikasi.');

        } catch (\Exception $e) {
            \Log::error('PPDB Verify Applicant Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memverifikasi pendaftar.');
        }
    }

    /**
     * Approve applicant
     */
    public function approveApplicant(Request $request, $id)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::findOrFail($id);

            // Check access
            if (auth()->user()->role !== 'foundation_admin' && 
                $applicant->school_unit_id !== auth()->user()->school_unit_id) {
                abort(403);
            }

            if ($applicant->status !== 'verified') {
                return redirect()->back()
                    ->with('error', 'Pendaftar harus diverifikasi terlebih dahulu.');
            }

            $applicant->status = 'approved';
            $applicant->approved_at = now();
            $applicant->approved_by = auth()->id();
            $applicant->save();

            // Send notification
            $this->sendStatusNotification($applicant, 'approved');

            // Clear cache
            $this->ppdbService->clearCache();

            return redirect()->back()
                ->with('success', 'Pendaftar berhasil disetujui.');

        } catch (\Exception $e) {
            \Log::error('PPDB Approve Applicant Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui pendaftar.');
        }
    }

    /**
     * Reject applicant
     */
    public function rejectApplicant(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $applicant = \App\Models\PPDBApplicant::findOrFail($id);

            // Check access
            if (auth()->user()->role !== 'foundation_admin' && 
                $applicant->school_unit_id !== auth()->user()->school_unit_id) {
                abort(403);
            }

            $applicant->status = 'rejected';
            $applicant->rejected_at = now();
            $applicant->rejected_by = auth()->id();
            $applicant->rejection_reason = $request->rejection_reason;
            $applicant->save();

            // Send notification
            $this->sendStatusNotification($applicant, 'rejected', $request->rejection_reason);

            // Clear cache
            $this->ppdbService->clearCache();

            return redirect()->back()
                ->with('success', 'Pendaftar berhasil ditolak.');

        } catch (\Exception $e) {
            \Log::error('PPDB Reject Applicant Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak pendaftar.');
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $id)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::findOrFail($id);

            // Check access
            if (auth()->user()->role !== 'foundation_admin' && 
                $applicant->school_unit_id !== auth()->user()->school_unit_id) {
                abort(403);
            }

            if ($applicant->status !== 'verified') {
                return redirect()->back()
                    ->with('error', 'Pendaftar harus dalam status verified.');
            }

            $result = $this->paymentService->verifyPayment($id, $request->all());

            if ($result['success']) {
                // Clear cache
                $this->ppdbService->clearCache();

                return redirect()->back()
                    ->with('success', 'Pembayaran berhasil diverifikasi.');
            }

            return redirect()->back()
                ->with('error', $result['message']);

        } catch (\Exception $e) {
            \Log::error('PPDB Verify Payment Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memverifikasi pembayaran.');
        }
    }

    /**
     * Display waves list
     */
    public function waves(Request $request)
    {
        try {
            $query = \App\Models\PPDBWave::query()->with(['school', 'major', 'fees']);

            // Apply foundation/school filter
            if (auth()->user()->role !== 'foundation_admin') {
                $query->where('school_unit_id', auth()->user()->school_unit_id);
            } elseif ($request->school_unit_id) {
                $query->where('school_unit_id', $request->school_unit_id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $waves = $query->latest()->paginate(20);
            $schools = \App\Models\SchoolUnit::pluck('name', 'id');

            return view('ppdb::admin.waves.index', compact('waves', 'schools'));
        } catch (\Exception $e) {
            \Log::error('PPDB Waves Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data gelombang.');
        }
    }

    /**
     * Create new wave
     */
    public function createWave()
    {
        try {
            $schools = \App\Models\SchoolUnit::pluck('name', 'id');
            $majors = \App\Models\Major::pluck('name', 'id');
            $academicYears = \App\Models\AcademicYear::pluck('name', 'id');

            return view('ppdb::admin.waves.create', compact('schools', 'majors', 'academicYears'));
        } catch (\Exception $e) {
            \Log::error('PPDB Create Wave Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat form gelombang.');
        }
    }

    /**
     * Store new wave
     */
    public function storeWave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'school_unit_id' => 'required|exists:school_units,id',
                'academic_year_id' => 'required|exists:academic_years,id',
                'major_id' => 'nullable|exists:majors,id',
                'description' => 'nullable|string',
                'quota' => 'nullable|integer|min:1',
                'registration_start' => 'required|date',
                'registration_end' => 'required|date|after:registration_start',
                'test_date' => 'nullable|date|after:registration_end',
                'announcement_date' => 'nullable|date|after:test_date',
                'status' => 'required|in:active,inactive,closed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $wave = \App\Models\PPDBWave::create([
                'name' => $request->name,
                'school_unit_id' => $request->school_unit_id,
                'academic_year_id' => $request->academic_year_id,
                'major_id' => $request->major_id,
                'description' => $request->description,
                'quota' => $request->quota,
                'registration_start' => $request->registration_start,
                'registration_end' => $request->registration_end,
                'test_date' => $request->test_date,
                'announcement_date' => $request->announcement_date,
                'status' => $request->status,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            // Clear cache
            $this->ppdbService->clearCache();

            return redirect()->route('ppdb.admin.waves.index')
                ->with('success', 'Gelombang berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PPDB Store Wave Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan gelombang.')
                ->withInput();
        }
    }

    /**
     * Toggle wave status
     */
    public function toggleWaveStatus($id)
    {
        try {
            $wave = \App\Models\PPDBWave::findOrFail($id);

            // Check access
            if (auth()->user()->role !== 'foundation_admin' && 
                $wave->school_unit_id !== auth()->user()->school_unit_id) {
                abort(403);
            }

            $wave->status = $wave->status === 'active' ? 'inactive' : 'active';
            $wave->save();

            // Clear cache
            $this->ppdbService->clearCache();

            return redirect()->back()
                ->with('success', 'Status gelombang berhasil diubah.');

        } catch (\Exception $e) {
            \Log::error('PPDB Toggle Wave Status Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status gelombang.');
        }
    }

    /**
     * Display fee components
     */
    public function feeComponents()
    {
        try {
            $query = \App\Models\PPDBFeeComponent::query();

            // Apply foundation/school filter
            if (auth()->user()->role !== 'foundation_admin') {
                $query->where('school_unit_id', auth()->user()->school_unit_id);
            }

            $components = $query->latest()->paginate(20);

            return view('ppdb::admin.fee-components.index', compact('components'));
        } catch (\Exception $e) {
            \Log::error('PPDB Fee Components Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data komponen biaya.');
        }
    }

    /**
     * Store fee component
     */
    public function storeFeeComponent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
                'type' => 'required|in:mandatory,optional',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            \App\Models\PPDBFeeComponent::create([
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'type' => $request->type,
                'is_active' => $request->is_active ?? true,
                'school_unit_id' => auth()->user()->role === 'foundation_admin' 
                    ? $request->school_unit_id 
                    : auth()->user()->school_unit_id,
                'created_by' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('success', 'Komponen biaya berhasil ditambahkan.');

        } catch (\Exception $e) {
            \Log::error('PPDB Store Fee Component Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan komponen biaya.');
        }
    }

    /**
     * Display settings
     */
    public function settings()
    {
        try {
            $settings = $this->ppdbService->getSettings();
            $paymentGateways = $this->paymentService->getAvailableGateways();

            return view('ppdb::admin.settings.index', compact('settings', 'paymentGateways'));
        } catch (\Exception $e) {
            \Log::error('PPDB Settings Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat pengaturan.');
        }
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        try {
            $settings = $request->except(['_token', '_method']);
            
            $this->ppdbService->updateSettings($settings);

            return redirect()->back()
                ->with('success', 'Pengaturan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('PPDB Update Settings Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan.');
        }
    }

    /**
     * Send status notification
     */
    private function sendStatusNotification($applicant, $status, $reason = null)
    {
        try {
            if (config('ppdb.email.enabled', true) && $applicant->email) {
                $mailClass = 'App\\Plugins\\PPDB\\Mail\\' . ucfirst($status) . 'Mail';
                
                if (class_exists($mailClass)) {
                    \Mail::to($applicant->email)->send(new $mailClass($applicant, $reason));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send status notification: ' . $e->getMessage());
        }
    }
}
