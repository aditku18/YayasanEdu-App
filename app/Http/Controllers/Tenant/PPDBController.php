<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class PPDBController extends Controller
{
    private function getSchoolSlug(Request $request): ?string
    {
        return $request->route('school');
    }
    
    private function getSchoolId(Request $request): ?int
    {
        $slug = $this->getSchoolSlug($request);
        if ($slug) {
            $school = SchoolUnit::where('slug', $slug)->first();
            return $school?->id;
        }
        return auth()->user()->school_unit_id;
    }
    
    private function getRedirectRoute(Request $request, string $routeName, $params = []): \Illuminate\Http\RedirectResponse
    {
        $schoolSlug = $this->getSchoolSlug($request);
        if ($schoolSlug) {
            return redirect()->route($routeName, array_merge(['school' => $schoolSlug], $params));
        }
        return redirect()->route($routeName, $params);
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $isFoundation = $user->role === 'foundation_admin';
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        
        $query = \App\Models\PPDBApplicant::query();
        
        if (!$isFoundation) {
            $query->where('school_unit_id', $user->school_unit_id);
        }

        $totalApplicants = $query->count();
        $unverified = (clone $query)->where('status', 'pending')->count();
        $verified = (clone $query)->where('status', 'verified')->count();
        $accepted = (clone $query)->where('status', 'accepted')->count();
        $rejected = (clone $query)->where('status', 'rejected')->count();
        $enrolled = (clone $query)->where('status', 'enrolled')->count();

        // Quota monitoring: load active waves with applicant counts
        $wavesQuery = \App\Models\PPDBWave::where('status', 'active');
        if (!$isFoundation) {
            $wavesQuery->where('school_unit_id', $user->school_unit_id);
        }
        $waves = $wavesQuery->with('major')->get();

        $totalQuota = 0;
        $totalUsed = 0;
        foreach ($waves as $wave) {
            $wave->applicants_count = \App\Models\PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
            if ($wave->quota !== null) {
                $totalQuota += $wave->quota;
                $totalUsed += $wave->applicants_count;
            }
        }
        $quotaRemaining = $totalQuota > 0 ? max(0, $totalQuota - $totalUsed) : null;

        return view('school.ppdb.dashboard', compact('totalApplicants', 'unverified', 'verified', 'accepted', 'rejected', 'enrolled', 'waves', 'quotaRemaining', 'totalQuota', 'schoolSlug'));
    }

    public function applicants(Request $request)
    {
        $user = auth()->user();
        $isFoundation = $user->role === 'foundation_admin';
        $schoolSlug = $this->getSchoolSlug($request);
        
        $query = \App\Models\PPDBApplicant::query()->with('wave');

        // Access Control
        if (!$isFoundation) {
            $query->where('school_unit_id', $user->school_unit_id);
        } elseif ($request->school_unit_id) {
            $query->where('school_unit_id', $request->school_unit_id);
        }

        // Filters
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->ppdb_wave_id) {
            $query->where('ppdb_wave_id', $request->ppdb_wave_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $applicants = $query->latest()->get();
        
        $waves = \App\Models\PPDBWave::when(!$isFoundation, function($q) use ($user) {
            return $q->where('school_unit_id', $user->school_unit_id);
        })->get();

        $units = $isFoundation ? \App\Models\SchoolUnit::all() : collect();
            
        return view('school.ppdb.applicants', compact('applicants', 'waves', 'units', 'isFoundation', 'schoolSlug'));
    }

    public function verifyApplicant($id)
    {
        $applicant = \App\Models\PPDBApplicant::findOrFail($id);
        $applicant->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pendaftar berhasil diverifikasi.');
    }

    public function showApplicant($id)
    {
        $applicant = \App\Models\PPDBApplicant::with(['wave.fees.component', 'major'])->findOrFail($id);
        
        // Timeline logic (basic for now)
        $timeline = collect([
            ['date' => $applicant->created_at, 'status' => 'Pendaftaran Akun', 'description' => 'Calon siswa mendaftarkan akun di portal PPDB.'],
        ]);

        if ($applicant->verified_at) {
            $timeline->push(['date' => $applicant->verified_at, 'status' => 'Berkas Diverifikasi', 'description' => 'Dokumen dan bukti bayar formulir telah diperiksa admin.']);
        }

        if ($applicant->status == 'accepted' || $applicant->status == 'enrolled') {
            $timeline->push(['date' => $applicant->updated_at, 'status' => 'Diterima', 'description' => 'Pendaftar dinyatakan Lulus Seleksi.']);
        }

        if ($applicant->status == 'enrolled') {
            $timeline->push(['date' => $applicant->final_payment_at, 'status' => 'Terdaftar (Lunas)', 'description' => 'Bukti pelunasan telah diverifikasi.']);
        }

        return view('school.ppdb.applicant-show', compact('applicant', 'timeline', 'schoolSlug'));
    }

    public function updateApplicantStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,accepted,rejected,enrolled'
        ]);

        $applicant = \App\Models\PPDBApplicant::findOrFail($id);
        $data = ['status' => $request->status];

        if ($request->status == 'verified' && !$applicant->verified_at) {
            $data['verified_at'] = now();
            $data['verified_by'] = auth()->id();
        }

        $applicant->update($data);

        return back()->with('success', 'Status pendaftar berhasil diperbarui menjadi ' . strtoupper($request->status));
    }

    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_type' => 'required|in:partial,full',
        ]);

        $applicant = \App\Models\PPDBApplicant::findOrFail($id);

        if ($request->payment_type === 'partial') {
            $applicant->update([
                'payment_status' => 'partial',
                'final_payment_at' => now(),
            ]);
            return back()->with('success', 'Pembayaran sebagian (DP 50%) berhasil dikonfirmasi. Siswa masih perlu melunasi sisa tagihan.');
        }

        // Full payment
        $applicant->update([
            'payment_status' => 'paid',
            'status' => 'enrolled',
            'final_payment_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran lunas! Siswa resmi terdaftar.');
    }

    public function settings(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $activeWaves = \App\Models\PPDBWave::where('school_unit_id', $schoolId)->with('major')->get();

        // Count applicants per wave for display
        foreach ($activeWaves as $wave) {
            $wave->applicants_count = \App\Models\PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
        }

        return view('school.ppdb.settings', compact('activeWaves', 'schoolSlug'));
    }

    public function createWave(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $academicYears = \App\Models\AcademicYear::all();
        $majors = \App\Models\Major::where('school_id', auth()->user()->school_unit_id)->get();
        return view('school.ppdb.waves.create', compact('academicYears', 'majors', 'schoolSlug'));
    }

    public function storeWave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'major_id' => 'nullable|exists:majors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'registration_fee' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
        ]);

        \App\Models\PPDBWave::create([
            'school_unit_id' => $this->getSchoolId($request),
            'academic_year_id' => $request->academic_year_id,
            'major_id' => $request->major_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'registration_fee' => $request->registration_fee,
            'quota' => $request->quota,
            'status' => 'active',
        ]);

        return $this->getRedirectRoute($request, 'tenant.ppdb.settings')->with('success', 'Gelombang PPDB berhasil dibuat.');
    }

    public function editWave(Request $request, $id)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $wave = \App\Models\PPDBWave::findOrFail($id);
        $academicYears = \App\Models\AcademicYear::all();
        $majors = \App\Models\Major::where('school_id', auth()->user()->school_unit_id)->get();
        return view('school.ppdb.waves.edit', compact('wave', 'academicYears', 'majors', 'schoolSlug'));
    }

    public function updateWave(Request $request, $id)
    {
        $wave = \App\Models\PPDBWave::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'major_id' => 'nullable|exists:majors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'registration_fee' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
        ]);

        $wave->update([
            'academic_year_id' => $request->academic_year_id,
            'major_id' => $request->major_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'registration_fee' => $request->registration_fee,
            'quota' => $request->quota,
        ]);

        return $this->getRedirectRoute($request, 'tenant.ppdb.settings')->with('success', 'Gelombang PPDB berhasil diperbarui.');
    }

    public function destroyWave($id)
    {
        $wave = \App\Models\PPDBWave::findOrFail($id);

        // Delete related wave fees first
        \App\Models\PPDBWaveFee::where('ppdb_wave_id', $wave->id)->delete();

        // Check for applicants
        $applicantCount = \App\Models\PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
        if ($applicantCount > 0) {
            return back()->with('error', "Gelombang tidak dapat dihapus karena sudah memiliki {$applicantCount} pendaftar. Nonaktifkan saja.");
        }

        $wave->delete();

        return $this->getRedirectRoute($request, 'tenant.ppdb.settings')->with('success', 'Gelombang PPDB berhasil dihapus.');
    }

    public function toggleWaveStatus($id)
    {
        $wave = \App\Models\PPDBWave::findOrFail($id);
        $wave->update([
            'status' => $wave->status === 'active' ? 'closed' : 'active',
        ]);

        $label = $wave->status === 'active' ? 'diaktifkan' : 'ditutup';
        return back()->with('success', "Gelombang berhasil {$label}.");
    }

    public function fees(Request $request, $waveId)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $wave = \App\Models\PPDBWave::with('fees.component')->findOrFail($waveId);
        $components = \App\Models\PPDBFeeComponent::where('school_unit_id', auth()->user()->school_unit_id)->get();
        $majors = \App\Models\Major::where('school_id', auth()->user()->school_unit_id)->get();
        
        return view('school.ppdb.waves.fees', compact('wave', 'components', 'majors', 'schoolSlug'));
    }

    public function updateFees(Request $request, $waveId)
    {
        $wave = \App\Models\PPDBWave::findOrFail($waveId);
        
        if ($request->has('fees')) {
            foreach ($request->fees as $majorId => $components) {
                $actualMajorId = $majorId === 'all' ? null : $majorId;
                foreach ($components as $componentId => $amount) {
                    if ($amount !== null && $amount !== '') {
                        \App\Models\PPDBWaveFee::updateOrCreate(
                            [
                                'ppdb_wave_id' => $wave->id,
                                'ppdb_fee_component_id' => $componentId,
                                'major_id' => $actualMajorId,
                            ],
                            ['amount' => $amount]
                        );
                    } else {
                        \App\Models\PPDBWaveFee::where([
                            'ppdb_wave_id' => $wave->id,
                            'ppdb_fee_component_id' => $componentId,
                            'major_id' => $actualMajorId,
                        ])->delete();
                    }
                }
            }
        }

        return $this->getRedirectRoute($request, 'tenant.ppdb.settings')->with('success', 'Rincian biaya berhasil diperbarui.');
    }

    public function storeFeeComponent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        \App\Models\PPDBFeeComponent::create([
            'school_unit_id' => auth()->user()->school_unit_id,
            'name' => $request->name,
            'is_mandatory' => true,
        ]);

        return back()->with('success', 'Komponen biaya berhasil ditambahkan.');
    }

    public function destroyFeeComponent($id)
    {
        $component = \App\Models\PPDBFeeComponent::where('school_unit_id', auth()->user()->school_unit_id)
            ->findOrFail($id);
            
        // Delete related wave fees first
        \App\Models\PPDBWaveFee::where('ppdb_fee_component_id', $component->id)->delete();
        
        $component->delete();

        return back()->with('success', 'Komponen biaya berhasil dihapus.');
    }
}

