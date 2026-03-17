<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PPDBWave;
use App\Models\PPDBApplicant;

class PPDBPublicController extends Controller
{
    public function index()
    {
        $waves = PPDBWave::where('status', 'active')->with(['fees', 'major'])->get();
        // Since we need to know how many applicants, we should calculate it
        foreach ($waves as $wave) {
            $wave->applicants_count = PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
            $wave->is_full = $wave->quota !== null && $wave->applicants_count >= $wave->quota;
        }

        return view('public.ppdb.index', compact('waves'));
    }

    public function register($waveId)
    {
        $wave = PPDBWave::with('fees.component')->findOrFail($waveId);

        // Check overall wave quota before proceeding to form
        if ($wave->quota !== null) {
            $waveApplicantCount = PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
            if ($waveApplicantCount >= $wave->quota) {
                return redirect()->route('tenant.ppdb.public.index')->with('error', 'Pendaftaran gagal: Kuota gelombang ini telah terpenuhi.');
            }
        }

        $majors = \App\Models\Major::where('school_id', $wave->school_unit_id)->get();
        // Calculate full status for each major
        foreach ($majors as $major) {
            if ($major->capacity !== null) {
                $majorApplicantCount = PPDBApplicant::where('major_id', $major->id)
                    ->where('academic_year_id', $wave->academic_year_id)->count();
                $major->is_full = $majorApplicantCount >= $major->capacity;
                $major->remaining = max(0, $major->capacity - $majorApplicantCount);
            } else {
                $major->is_full = false;
                $major->remaining = null;
            }
        }

        // Check if the required specific major is full
        if ($wave->major_id) {
            $requiredMajor = $majors->firstWhere('id', $wave->major_id);
            if ($requiredMajor && $requiredMajor->is_full) {
                return redirect()->route('tenant.ppdb.public.index')->with('error', 'Pendaftaran gagal: Daya tampung untuk jurusan ini telah terpenuhi.');
            }
        }

        return view('public.ppdb.register', compact('wave', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ppdb_wave_id' => 'required|exists:p_p_d_b_waves,id',
            'major_id' => 'required|exists:majors,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'nisn' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'pob' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'previous_school' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
        ]);

        $wave = PPDBWave::findOrFail($request->ppdb_wave_id);
        
        // Enforce major_id from wave if the wave is major-specific
        $finalMajorId = $wave->major_id ?? $request->major_id;

        // Generate Registration Number
        $year = date('Y');
        $count = PPDBApplicant::whereYear('created_at', $year)->count() + 1;
        $regNumber = "PPDB-" . $year . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Check Wave Quota
        if ($wave->quota !== null) {
            $waveApplicantCount = PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
            if ($waveApplicantCount >= $wave->quota) {
                return back()->with('error', 'Maaf, kuota untuk gelombang ini sudah terpenuhi.')->withInput();
            }
        }

        // Check Major Capacity
        $major = \App\Models\Major::findOrFail($finalMajorId);
        if ($major->capacity !== null) {
            // Check based on the active academic year if possible, but ideally we check total valid applicants for this major in this academic year
            $majorApplicantCount = PPDBApplicant::where('major_id', $finalMajorId)
                                                ->where('academic_year_id', $wave->academic_year_id)
                                                ->count();
            if ($majorApplicantCount >= $major->capacity) {
                return back()->with('error', 'Maaf, daya tampung untuk jurusan ini sudah terpenuhi.')->withInput();
            }
        }

        // Calculate Total Fee based on common and major-specific fees
        $totalFee = $wave->registration_fee;
        
        // Add common fees (where major_id is null)
        $totalFee += $wave->fees->where('major_id', null)->sum('amount');
        
        // Add major specific fees (where major_id matches applicant's choice)
        $totalFee += $wave->fees->where('major_id', $finalMajorId)->sum('amount');

        $applicant = PPDBApplicant::create(array_merge($validated, [
            'school_unit_id' => $wave->school_unit_id,
            'registration_number' => $regNumber,
            'total_fee' => $totalFee,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]));

        return redirect()->route('tenant.ppdb.public.success', $applicant->id)
            ->with('success', 'Pendaftaran awal berhasil! Silakan simpan nomor pendaftaran Anda.');
    }

    public function success($id)
    {
        $applicant = PPDBApplicant::with('wave')->findOrFail($id);
        return view('public.ppdb.success', compact('applicant'));
    }

    public function checkStatus()
    {
        return view('public.ppdb.check-status');
    }

    public function tracking(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
            'phone' => 'required|string',
        ]);

        $applicant = PPDBApplicant::where('registration_number', $request->registration_number)
            ->where('phone', $request->phone)
            ->first();

        if (!$applicant) {
            return back()->with('error', 'Nomor Pendaftaran atau Nomor WA tidak ditemukan.');
        }

        return redirect()->route('tenant.ppdb.public.upload', $applicant->registration_number);
    }

    public function upload($regNumber)
    {
        $applicant = PPDBApplicant::with(['wave.fees.component', 'major'])
            ->where('registration_number', $regNumber)
            ->firstOrFail();

        // calculate fee summary for convenience in the view
        $filteredFees = $applicant->wave->fees->filter(function ($fee) use ($applicant) {
            return is_null($fee->major_id) || $fee->major_id == $applicant->major_id;
        });
        $subTotal = $filteredFees->sum('amount');
        $applicant->fee_sub_total = $subTotal;
        $applicant->fee_minimum = $subTotal * 0.5;

        return view('public.ppdb.upload', compact('applicant'));
    }

    public function storeDocuments(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
            'document_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'final_payment_proof' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $applicant = PPDBApplicant::where('registration_number', $request->registration_number)->firstOrFail();

        $docs = [];
        $uploadedCount = 0;
        foreach (['document_kk', 'document_akta', 'document_ijazah', 'document_foto', 'payment_proof', 'final_payment_proof'] as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('ppdb/documents', 'public');
                $docs[$field] = $path;
                if ($field === 'final_payment_proof') {
                    $docs['final_payment_at'] = now();
                    $docs['payment_status'] = 'paid';
                }
                $uploadedCount++;
            }
        }

        if ($uploadedCount === 0) {
            return back()->withErrors(['pilih_file' => 'Tidak ada berkas baru yang dipilih atau ukuran berkas terlalu besar.']);
        }

        $applicant->update($docs);

        return back()->with('success', $uploadedCount . ' berkas berhasil diunggah!');
    }
}
