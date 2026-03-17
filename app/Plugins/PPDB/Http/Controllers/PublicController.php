<?php

namespace App\Plugins\PPDB\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\PPDB\Services\PPDBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PublicController extends Controller
{
    protected $ppdbService;

    public function __construct(PPDBService $ppdbService)
    {
        $this->ppdbService = $ppdbService;
    }

    /**
     * Display public PPDB portal
     */
    public function index()
    {
        try {
            $waves = $this->ppdbService->getActiveWaves();
            
            return view('ppdb::public.index', compact('waves'));
        } catch (\Exception $e) {
            \Log::error('PPDB Public Index Error: ' . $e->getMessage());
            
            return view('ppdb::public.error', [
                'message' => 'Sistem sedang dalam perbaikan. Silakan coba kembali nanti.'
            ]);
        }
    }

    /**
     * Show registration form for specific wave
     */
    public function register($waveId)
    {
        try {
            $check = $this->ppdbService->isWaveAvailable($waveId);
            
            if (!$check['available']) {
                return redirect()->route('ppdb.public.index')
                    ->with('error', $check['reason']);
            }

            $wave = $check['wave'];
            
            // Get available majors for the wave's school
            $majors = \App\Models\Major::where('school_id', $wave->school_unit_id)->get();
            
            // Calculate major capacity
            foreach ($majors as $major) {
                if ($major->capacity !== null) {
                    $majorApplicantCount = \App\Models\PPDBApplicant::where('major_id', $major->id)
                        ->where('academic_year_id', $wave->academic_year_id)
                        ->count();
                    $major->is_full = $majorApplicantCount >= $major->capacity;
                    $major->remaining = max(0, $major->capacity - $majorApplicantCount);
                } else {
                    $major->is_full = false;
                    $major->remaining = null;
                }
            }

            return view('ppdb::public.register', compact('wave', 'majors'));
        } catch (\Exception $e) {
            \Log::error('PPDB Register Error: ' . $e->getMessage());
            
            return redirect()->route('ppdb.public.index')
                ->with('error', 'Terjadi kesalahan saat memuat form pendaftaran.');
        }
    }

    /**
     * Store new applicant registration
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateRegistration($request);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $waveId = $request->input('ppdb_wave_id');
            $check = $this->ppdbService->isWaveAvailable($waveId);
            
            if (!$check['available']) {
                return redirect()->route('ppdb.public.index')
                    ->with('error', $check['reason']);
            }

            DB::beginTransaction();
            
            // Generate registration number
            $registrationNumber = $this->ppdbService->generateRegistrationNumber($waveId);
            
            // Create applicant
            $applicant = new \App\Models\PPDBApplicant();
            $applicant->registration_number = $registrationNumber;
            $applicant->ppdb_wave_id = $waveId;
            $applicant->school_unit_id = $check['wave']->school_unit_id;
            $applicant->academic_year_id = $check['wave']->academic_year_id;
            $applicant->major_id = $request->input('major_id');
            $applicant->name = $request->input('name');
            $applicant->email = $request->input('email');
            $applicant->phone = $request->input('phone');
            $applicant->nik = $request->input('nik');
            $applicant->place_of_birth = $request->input('place_of_birth');
            $applicant->date_of_birth = $request->input('date_of_birth');
            $applicant->gender = $request->input('gender');
            $applicant->address = $request->input('address');
            $applicant->village = $request->input('village');
            $applicant->district = $request->input('district');
            $applicant->city = $request->input('city');
            $applicant->province = $request->input('province');
            $applicant->postal_code = $request->input('postal_code');
            
            // Parent information
            $applicant->father_name = $request->input('father_name');
            $applicant->father_phone = $request->input('father_phone');
            $applicant->father_occupation = $request->input('father_occupation');
            $applicant->mother_name = $request->input('mother_name');
            $applicant->mother_phone = $request->input('mother_phone');
            $applicant->mother_occupation = $request->input('mother_occupation');
            $applicant->parent_address = $request->input('parent_address');
            
            // Previous school
            $applicant->previous_school = $request->input('previous_school');
            $applicant->previous_school_address = $request->input('previous_school_address');
            $applicant->graduation_year = $request->input('graduation_year');
            $applicant->graduation_certificate_number = $request->input('graduation_certificate_number');
            
            // Additional information
            $applicant->religion = $request->input('religion');
            $applicant->blood_type = $request->input('blood_type');
            $applicant->height = $request->input('height');
            $applicant->weight = $request->input('weight');
            $applicant->special_needs = $request->input('special_needs');
            $applicant->hobbies = $request->input('hobbies');
            $applicant->achievements = $request->input('achievements');
            
            // System fields
            $applicant->status = 'pending';
            $applicant->sequence_number = $this->getSequenceNumber($waveId);
            $applicant->ip_address = $request->ip();
            $applicant->user_agent = $request->userAgent();
            $applicant->registered_at = now();
            
            $applicant->save();

            DB::commit();

            // Send registration confirmation email
            $this->sendRegistrationConfirmation($applicant);

            // Clear cache
            $this->ppdbService->clearCache();

            return redirect()->route('ppdb.public.success', $registrationNumber)
                ->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PPDB Store Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show registration success page
     */
    public function success($registrationNumber)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::where('registration_number', $registrationNumber)
                ->with(['wave', 'major', 'school'])
                ->firstOrFail();

            return view('ppdb::public.success', compact('applicant'));
        } catch (\Exception $e) {
            \Log::error('PPDB Success Error: ' . $e->getMessage());
            
            return redirect()->route('ppdb.public.index')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }
    }

    /**
     * Show status check form
     */
    public function checkStatus()
    {
        return view('ppdb::public.check-status');
    }

    /**
     * Track application status
     */
    public function tracking(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $applicant = \App\Models\PPDBApplicant::where('registration_number', $request->registration_number)
                ->where('email', $request->email)
                ->with(['wave', 'major', 'school'])
                ->firstOrFail();

            return view('ppdb::public.status-result', compact('applicant'));
        } catch (\Exception $e) {
            \Log::error('PPDB Tracking Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan. Periksa nomor pendaftaran dan email Anda.');
        }
    }

    /**
     * Show document upload form
     */
    public function upload($registrationNumber)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::where('registration_number', $registrationNumber)
                ->with(['wave', 'major'])
                ->firstOrFail();

            // Check if applicant can upload documents
            if (!in_array($applicant->status, ['pending', 'verified'])) {
                return redirect()->route('ppdb.public.check-status')
                    ->with('error', 'Dokumen tidak dapat diunggah pada status saat ini.');
            }

            return view('ppdb::public.upload', compact('applicant'));
        } catch (\Exception $e) {
            \Log::error('PPDB Upload Error: ' . $e->getMessage());
            
            return redirect()->route('ppdb.public.check-status')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }
    }

    /**
     * Store uploaded documents
     */
    public function storeDocuments(Request $request, $registrationNumber)
    {
        try {
            $applicant = \App\Models\PPDBApplicant::where('registration_number', $registrationNumber)
                ->firstOrFail();

            // Check if applicant can upload documents
            if (!in_array($applicant->status, ['pending', 'verified'])) {
                return redirect()->back()
                    ->with('error', 'Dokumen tidak dapat diunggah pada status saat ini.');
            }

            $validator = Validator::make($request->all(), [
                'birth_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'family_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'graduation_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $documents = [];
            $uploadPath = 'ppdb/documents/' . $applicant->id;

            // Upload each document
            foreach (['birth_certificate', 'family_card', 'graduation_certificate', 'photo'] as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    
                    $path = $file->storeAs($uploadPath, $filename, 'public');
                    $documents[$field] = $path;
                }
            }

            // Update applicant documents
            if (!empty($documents)) {
                $currentDocuments = $applicant->documents ?? [];
                $applicant->documents = array_merge($currentDocuments, $documents);
                $applicant->documents_uploaded_at = now();
                $applicant->save();
            }

            return redirect()->back()
                ->with('success', 'Dokumen berhasil diunggah.');

        } catch (\Exception $e) {
            \Log::error('PPDB Store Documents Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunggah dokumen.');
        }
    }

    /**
     * Validate registration data
     */
    private function validateRegistration(Request $request)
    {
        $rules = [
            'ppdb_wave_id' => 'required|exists:ppdb_waves,id',
            'major_id' => 'required|exists:majors,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nik' => 'required|string|size:16|unique:ppdb_applicants,nik',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:L,P',
            'address' => 'required|string|max:1000',
            'village' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'father_name' => 'required|string|max:255',
            'father_phone' => 'required|string|max:20',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mother_phone' => 'required|string|max:20',
            'mother_occupation' => 'nullable|string|max:255',
            'previous_school' => 'required|string|max:255',
            'graduation_year' => 'required|integer|digits:4',
            'religion' => 'required|string|max:50',
        ];

        $messages = [
            'required' => 'Field :attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'unique' => ':attribute sudah terdaftar.',
            'before' => 'Tanggal lahir harus sebelum hari ini.',
            'in' => 'Pilihan :attribute tidak valid.',
            'max' => 'Maksimal karakter :attribute adalah :max.',
            'size' => ':attribute harus :size karakter.',
            'digits' => ':attribute harus :digits digit.',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Get sequence number for wave
     */
    private function getSequenceNumber($waveId): int
    {
        $year = now()->year;
        $lastSequence = \App\Models\PPDBApplicant::where('ppdb_wave_id', $waveId)
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->max('sequence_number') ?? 0;
        
        return $lastSequence + 1;
    }

    /**
     * Send registration confirmation email
     */
    private function sendRegistrationConfirmation($applicant)
    {
        try {
            if (config('ppdb.email.enabled', true) && $applicant->email) {
                \Mail::to($applicant->email)->send(
                    new \App\Plugins\PPDB\Mail\RegistrationConfirmationMail($applicant)
                );
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send registration confirmation: ' . $e->getMessage());
        }
    }
}
