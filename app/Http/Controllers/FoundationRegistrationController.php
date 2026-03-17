<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Plugin;
use App\Models\Foundation;
use App\Models\User;
use App\Models\Subscription;
use App\Services\PluginInstallationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;

class FoundationRegistrationController extends Controller
{
    protected $steps = ['institution', 'documents', 'package', 'admin']; // 4 steps + success
    
    public function __construct()
    {
        $this->middleware('guest')->except(['success', 'reset']);
    }

    /**
     * Display step 1: Institution Data
     */
    public function step1(Request $request)
    {
        $data = session('registration_data', []);
        
        // Check if a plan is specified in the query parameter
        if ($request->has('plan') && empty($data['step3'])) {
            $preSelectedPlan = Plan::find($request->plan);
            if ($preSelectedPlan) {
                $data['step3'] = [
                    'plan_id' => $preSelectedPlan->id,
                    'plan' => $preSelectedPlan
                ];
                session(['registration_data' => $data]);
            }
        }
        
        return view('register-foundation', [
            'step' => 1,
            'stepName' => 'Data Institusi',
            'data' => $data,
            'plans' => $this->getPlans(),
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process step 1: Save institution data
     */
    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'foundation_name' => 'required|string|max:100',
            'institution_type' => 'required|in:Yayasan,Sekolah,Lembaga Kursus',
            'npsn' => 'nullable|digits_between:8,10',
            'education_levels' => 'required|array|min:1',
            'student_count' => 'required|integer|min:1|max:99999',
            'address' => 'required|string|max:500',
            'province' => 'required|string',
            'regency' => 'required|string',
            'phone' => 'required|digits_between:10,13',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
        ], [
            'foundation_name.required' => 'Nama Yayasan/Sekolah wajib diisi.',
            'institution_type.required' => 'Jenis Institusi wajib dipilih.',
            'education_levels.required' => 'Pilih minimal satu Jenjang Pendidikan.',
            'student_count.required' => 'Jumlah Siswa wajib diisi.',
            'student_count.min' => 'Jumlah Siswa minimal 1.',
            'student_count.max' => 'Jumlah Siswa maksimal 99999.',
            'address.required' => 'Alamat Lengkap wajib diisi.',
            'province.required' => 'Provinsi wajib dipilih.',
            'regency.required' => 'Kabupaten/Kota wajib dipilih.',
            'phone.required' => 'Nomor Telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'email.required' => 'Email Resmi wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'website.url' => 'Format URL tidak valid.',
        ]);

        // Store in session
        $data = session('registration_data', []);
        $data['step1'] = $validated;
        session(['registration_data' => $data]);

        return redirect()->route('register.foundation.step2');
    }

    /**
     * Get file type from file path
     */
    private function getFileTypeFromPath($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'application/pdf';
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            default:
                return 'application/octet-stream';
        }
    }

    /**
     * Display step 2: Document Upload
     */
    public function step2()
    {
        $data = session('registration_data', []);
        
        if (empty($data) || !isset($data['step1'])) {
            return redirect()->route('register.foundation.step1');
        }
        
        return view('register-foundation', [
            'step' => 2,
            'stepName' => 'Upload Dokumen',
            'data' => $data,
            'plans' => $this->getPlans(),
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process step 2: Save document uploads
     */
    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'sk_pendirian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'npsn_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'logo' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'building_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'ktp' => 'required|file|mimes:jpg,jpeg,png|max:1024',
        ], [
            'sk_pendirian.required' => 'SK Pendirian wajib diupload.',
            'sk_pendirian.mimes' => 'SK Pendirian harus berformat PDF, JPG, atau PNG.',
            'sk_pendirian.max' => 'Ukuran SK Pendirian maksimal 2MB.',
            'logo.required' => 'Logo yayasan wajib diupload.',
            'logo.mimes' => 'Logo harus berformat JPG atau PNG.',
            'logo.max' => 'Ukuran logo maksimal 1MB.',
            'building_photo.required' => 'Foto gedung wajib diupload.',
            'building_photo.mimes' => 'Foto gedung harus berformat JPG atau PNG.',
            'building_photo.max' => 'Ukuran foto gedung maksimal 2MB.',
            'ktp.required' => 'KTP penanggung jawab wajib diupload.',
            'ktp.mimes' => 'KTP harus berformat JPG atau PNG.',
            'ktp.max' => 'Ukuran KTP maksimal 1MB.',
        ]);

        // Store files temporarily
        $data = session('registration_data', []);
        $data['step2'] = [];
        
        foreach ($validated as $key => $file) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('temp/documents', 'public');
                $data['step2'][$key] = $path;
            }
        }
        
        session(['registration_data' => $data]);

        return redirect()->route('register.foundation.step3');
    }

    /**
     * Display step 3: Package Selection
     */
    public function step3()
    {
        $data = session('registration_data', []);
        
        if (empty($data) || !isset($data['step1']) || !isset($data['step2'])) {
            return redirect()->route('register.foundation.step1');
        }
        
        // Get plans with included plugins
        $plans = Plan::with('includedPlugins')->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
        
        return view('register-foundation', [
            'step' => 3,
            'stepName' => 'Pilih Paket',
            'data' => $data,
            'plans' => $plans,
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process step 3: Save selected package
     */
    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'additional_plugins' => 'nullable|array',
            'additional_plugins.*' => 'exists:plugins,id',
        ], [
            'plan_id.required' => 'Silakan pilih paket langganan.',
            'plan_id.exists' => 'Paket yang dipilih tidak valid.',
            'additional_plugins.*.exists' => 'Plugin yang dipilih tidak tersedia.',
        ]);

        $plan = Plan::with('includedPlugins')->find($validated['plan_id']);
        
        // Validate plugin slots
        $totalPlugins = count($plan->included_plugins ?? []) + 
                       count($validated['additional_plugins'] ?? []);
        
        if ($totalPlugins > ($plan->plugin_slots ?? 0)) {
            return back()->withErrors([
                'plugin_slots' => "Maksimal {$plan->plugin_slots} plugin untuk paket {$plan->name}"
            ])->withInput();
        }
        
        // Store only serializable data in session
        $data = session('registration_data', []);
        $data['step3'] = [
            'plan_id' => $validated['plan_id'],
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'price_per_month' => $plan->price_per_month,
                'max_students' => $plan->max_students,
                'included_plugins' => $plan->included_plugins ?? [],
                'plugin_slots' => $plan->plugin_slots ?? 0,
            ],
            'additional_plugins' => $validated['additional_plugins'] ?? [],
        ];
        session(['registration_data' => $data]);

        return redirect()->route('register.foundation.step4');
    }

    /**
     * Display step 4: Admin Account
     */
    public function step4()
    {
        $data = session('registration_data', []);
        
        if (empty($data) || !isset($data['step1']) || !isset($data['step2']) || !isset($data['step3'])) {
            return redirect()->route('register.foundation.step1');
        }
        
        return view('register-foundation', [
            'step' => 4,
            'stepName' => 'Akun Admin',
            'data' => $data,
            'plans' => $this->getPlans(),
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process step 4: Complete registration
     */
    public function postStep4(Request $request)
    {
        // Debug: Log incoming request
        \Log::info('postStep4 called', [
            'request_method' => $request->method(),
            'has_data' => $request->has('admin_name'),
            'all_inputs' => $request->all()
        ]);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            \Log::info('AJAX request detected');
        } else {
            \Log::info('Regular form submission detected');
        }

        $validated = $request->validate([
            'admin_name' => 'required|string|min:2|max:100',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_phone' => 'required|string|max:15',
            'password' => [
                'required',
                'string',
                'min:8',
            ],
            'password_confirmation' => 'required|same:password',
        ], [
            'admin_name.required' => 'Nama Admin wajib diisi.',
            'admin_name.min' => 'Nama minimal 2 karakter.',
            'admin_email.required' => 'Email Admin wajib diisi.',
            'admin_email.email' => 'Format email tidak valid.',
            'admin_email.unique' => 'Email ini sudah terdaftar.',
            'admin_phone.required' => 'Nomor HP wajib diisi.',
            'admin_phone.max' => 'Nomor HP maksimal 15 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password_confirmation.required' => 'Konfirmasi Password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi Password tidak cocok.',
        ]);

        \Log::info('postStep4 validation passed', ['validated' => $validated]);

        $data = session('registration_data', []);
        $data['step4'] = [
            'admin_name' => $validated['admin_name'],
            'admin_email' => $validated['admin_email'],
            'admin_phone' => $validated['admin_phone'],
            'password' => $validated['password'],
        ];
        session(['registration_data' => $data]);

        \Log::info('postStep4 calling completeRegistration', ['data_keys' => array_keys($data)]);

        $result = $this->completeRegistration($data);
        
        \Log::info('completeRegistration returned', ['result_type' => gettype($result)]);
        
        // If this is AJAX, return JSON response
        if ($request->ajax()) {
            if ($result instanceof \Illuminate\Http\RedirectResponse) {
                return response()->json([
                    'success' => true,
                    'redirect' => $result->getTargetUrl()
                ]);
            }
            return response()->json(['success' => false, 'errors' => 'Registration failed']);
        }
        
        return $result;
    }
    /**
     * Complete the registration process
     */
    private function completeRegistration($registrationData)
    {
        try {
            \Log::info('Starting registration completion', ['data_keys' => array_keys($registrationData)]);
            
            // Validate required data
            if (!isset($registrationData['step1']) || !isset($registrationData['step3']) || !isset($registrationData['step4'])) {
                \Log::error('Missing required registration steps', [
                    'has_step1' => isset($registrationData['step1']),
                    'has_step3' => isset($registrationData['step3']),
                    'has_step4' => isset($registrationData['step4'])
                ]);
                throw new \Exception('Data registrasi tidak lengkap');
            }

            // Create user first
            \Log::info('Creating user', ['email' => $registrationData['step4']['admin_email']]);
            $user = User::create([
                'name' => $registrationData['step4']['admin_name'],
                'email' => $registrationData['step4']['admin_email'],
                'password' => Hash::make($registrationData['step4']['password']),
                'tenant_id' => null,
                'role' => 'foundation_admin',
                'is_active' => true,
            ]);

            \Log::info('User created successfully', ['user_id' => $user->id]);

            // Create foundation
            $subdomain = $this->generateSubdomain($registrationData['step1']['foundation_name']);
            
            // Get plan first to determine trial duration
            $plan = Plan::find($registrationData['step3']['plan_id']);
            if (!$plan) {
                throw new \Exception('Paket langganan tidak ditemukan');
            }

            \Log::info('Creating foundation', ['name' => $registrationData['step1']['foundation_name']]);
            $foundation = Foundation::create([
                'tenant_id' => null,
                'name' => $registrationData['step1']['foundation_name'],
                'email' => $registrationData['step1']['email'],
                'phone' => $registrationData['step1']['phone'],
                'address' => $registrationData['step1']['address'],
                'province' => $registrationData['step1']['province'],
                'regency' => $registrationData['step1']['regency'],
                'npsn' => $registrationData['step1']['npsn'] ?? null,
                'institution_type' => $registrationData['step1']['institution_type'] ?? 'yayasan',
                'education_levels' => json_encode($registrationData['step1']['education_levels'] ?? []),
                'student_count' => $registrationData['step1']['student_count'] ?? 0,
                'website' => $registrationData['step1']['website'] ?? null,
                'subdomain' => $subdomain,
                'status' => 'pending',
                'plan_id' => $registrationData['step3']['plan_id'],
                'admin_user_id' => $user->id,
                'trial_ends_at' => now()->addDays($plan->duration_days ?? 14),
            ]);

            \Log::info('Foundation created successfully', ['foundation_id' => $foundation->id]);

            // Process document uploads
            if (isset($registrationData['step2'])) {
                \Log::info('Processing document uploads');
                $this->processDocumentUploads($registrationData['step2'], $foundation);
            }

            // Clear session
            session()->forget('registration_data');

            // Send verification email
            event(new Registered($user));

            // Auto login
            Auth::login($user);

            \Log::info('Registration completed successfully', ['user_id' => $user->id, 'foundation_id' => $foundation->id]);

            return redirect()->route('register.foundation.success');

        } catch (\Exception $e) {
            // Log error and show user-friendly message
            \Log::error('Registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'registration_data' => $registrationData
            ]);
            
            return back()->withErrors([
                'registration' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Reset registration
     */
    public function reset()
    {
        session()->forget('registration_data');
        return redirect()->route('register.foundation.step1');
    }

    /**
     * Success page
     */
    public function success()
    {
        return view('registration-success');
    }

    /**
     * Get available plans
     */
    private function getPlans()
    {
        return Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    /**
     * Get available plugins
     */
    private function getPlugins()
    {
        return Plugin::where('is_available_in_marketplace', true)
            ->where('status', 'active')
            ->get();
    }

    /**
     * AJAX: Get plugin details by IDs
     */
    public function getPluginsByIds(Request $request)
    {
        // Handle both array and comma-separated string input
        $pluginIds = $request->input('plugin_ids', []);
        
        // If it's a string (from JSON.stringify), try to decode it
        if (is_string($pluginIds)) {
            $decoded = json_decode($pluginIds, true);
            if (is_array($decoded)) {
                $pluginIds = $decoded;
            } else {
                // Try comma-separated
                $pluginIds = array_filter(array_map('trim', explode(',', $pluginIds)));
            }
        }
        
        if (empty($pluginIds)) {
            return response()->json(['plugins' => [], 'total' => 0]);
        }
        
        $plugins = Plugin::whereIn('id', $pluginIds)
            ->where('is_available_in_marketplace', true)
            ->where('status', 'active')
            ->get(['id', 'name', 'price']);
        
        $total = $plugins->sum('price');
        
        return response()->json([
            'plugins' => $plugins->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'formatted_price' => 'Rp' . number_format($p->price, 0, ',', '.')
                ];
            }),
            'total' => $total,
            'formatted_total' => 'Rp' . number_format($total, 0, ',', '.')
        ]);
    }

    /**
     * Generate subdomain from foundation name
     */
    private function generateSubdomain($foundationName)
    {
        // Convert to lowercase and replace spaces/special chars with hyphens
        $subdomain = strtolower($foundationName);
        $subdomain = preg_replace('/[^a-z0-9]+/', '-', $subdomain);
        $subdomain = trim($subdomain, '-');
        
        // Ensure it's not empty
        if (empty($subdomain)) {
            $subdomain = 'foundation-' . time();
        }
        
        // Check if subdomain already exists and append number if needed
        $originalSubdomain = $subdomain;
        $counter = 1;
        
        while (Foundation::where('subdomain', $subdomain)->exists()) {
            $subdomain = $originalSubdomain . '-' . $counter;
            $counter++;
        }
        
        return $subdomain;
    }

    /**
     * Get Indonesian provinces
     */
    private function getProvinces()
    {
        return [
            ['id' => '11', 'name' => 'Aceh'],
            ['id' => '12', 'name' => 'Sumatera Utara'],
            ['id' => '13', 'name' => 'Sumatera Barat'],
            ['id' => '14', 'name' => 'Riau'],
            ['id' => '15', 'name' => 'Jambi'],
            ['id' => '16', 'name' => 'Sumatera Selatan'],
            ['id' => '17', 'name' => 'Bengkulu'],
            ['id' => '18', 'name' => 'Lampung'],
            ['id' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['id' => '21', 'name' => 'Kepulauan Riau'],
            ['id' => '31', 'name' => 'DKI Jakarta'],
            ['id' => '32', 'name' => 'Jawa Barat'],
            ['id' => '33', 'name' => 'Jawa Tengah'],
            ['id' => '34', 'name' => 'DI Yogyakarta'],
            ['id' => '35', 'name' => 'Jawa Timur'],
            ['id' => '36', 'name' => 'Banten'],
            ['id' => '51', 'name' => 'Bali'],
            ['id' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['id' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['id' => '61', 'name' => 'Kalimantan Barat'],
            ['id' => '62', 'name' => 'Kalimantan Tengah'],
            ['id' => '63', 'name' => 'Kalimantan Selatan'],
            ['id' => '64', 'name' => 'Kalimantan Timur'],
            ['id' => '65', 'name' => 'Kalimantan Utara'],
            ['id' => '71', 'name' => 'Sulawesi Utara'],
            ['id' => '72', 'name' => 'Sulawesi Tengah'],
            ['id' => '73', 'name' => 'Sulawesi Selatan'],
            ['id' => '74', 'name' => 'Sulawesi Tenggara'],
            ['id' => '75', 'name' => 'Gorontalo'],
            ['id' => '76', 'name' => 'Sulawesi Barat'],
            ['id' => '81', 'name' => 'Maluku'],
            ['id' => '82', 'name' => 'Maluku Utara'],
            ['id' => '91', 'name' => 'Papua Barat'],
            ['id' => '94', 'name' => 'Papua'],
        ];
    }

    /**
     * Process document uploads - move from temp to permanent storage
     */
    private function processDocumentUploads($step2Data, $foundation)
    {
        $documents = [
            'sk_pendirian' => 'sk_pendirian',
            'npsn_document' => 'npsn_izin',
            'logo' => 'logo',
            'building_photo' => 'gedung',
            'ktp' => 'ktp',
        ];

        $uploadPath = 'uploads/foundations/' . $foundation->id;

        foreach ($documents as $sessionKey => $diskPath) {
            if (isset($step2Data[$sessionKey]) && Storage::disk('public')->exists($step2Data[$sessionKey])) {
                $fileName = $diskPath . '.' . pathinfo($step2Data[$sessionKey], PATHINFO_EXTENSION);
                Storage::disk('public')->move($step2Data[$sessionKey], $uploadPath . '/' . $fileName);
                
                // Update foundation with document path using correct column names
                $columnPath = $diskPath . '_path';
                $foundation->update([$columnPath => $uploadPath . '/' . $fileName]);
            }
        }
    }

    /**
     * API: Check email uniqueness
     */
    public function checkEmail(Request $request)
    {
        if ($request->ajax()) {
            $exists = User::where('email', $request->email)->exists();
            return response()->json(['exists' => $exists]);
        }
        return abort(404);
    }

    /**
     * Get regencies based on province (for AJAX)
     */
    public function getRegencies(Request $request)
    {
        $provinceId = $request->query('province_id');
        $provinceName = $request->query('province_name');
        
        // If province_name is provided, find the corresponding province ID
        if ($provinceName && !$provinceId) {
            $provinces = $this->getProvinces();
            foreach ($provinces as $province) {
                if ($province['name'] === $provinceName) {
                    $provinceId = $province['id'];
                    break;
                }
            }
        }
        
        $regencies = [
            '11' => [
                ['id' => '1101', 'name' => 'Kabupaten Aceh Barat'],
                ['id' => '1102', 'name' => 'Kabupaten Aceh Barat Daya'],
                ['id' => '1103', 'name' => 'Kabupaten Aceh Besar'],
                ['id' => '1104', 'name' => 'Kabupaten Aceh Jaya'],
                ['id' => '1105', 'name' => 'Kabupaten Aceh Utara'],
                ['id' => '1106', 'name' => 'Kabupaten Aceh Timur'],
                ['id' => '1107', 'name' => 'Kabupaten Aceh Tengah'],
                ['id' => '1108', 'name' => 'Kabupaten Aceh Tenggara'],
                ['id' => '1109', 'name' => 'Kabupaten Aceh Singkil'],
                ['id' => '1110', 'name' => 'Kabupaten Simeulue'],
                ['id' => '1111', 'name' => 'Kota Banda Aceh'],
                ['id' => '1112', 'name' => 'Kota Sabang'],
                ['id' => '1113', 'name' => 'Kota Lhokseumawe'],
                ['id' => '1114', 'name' => 'Kota Langsa'],
                ['id' => '1115', 'name' => 'Kota Subulussalam'],
            ],
            '31' => [
                ['id' => '3101', 'name' => 'Kabupaten Kepulauan Seribu'],
                ['id' => '3102', 'name' => 'Kota Jakarta Selatan'],
                ['id' => '3103', 'name' => 'Kota Jakarta Timur'],
                ['id' => '3104', 'name' => 'Kota Jakarta Pusat'],
                ['id' => '3105', 'name' => 'Kota Jakarta Barat'],
                ['id' => '3106', 'name' => 'Kota Jakarta Utara'],
            ],
            '32' => [
                ['id' => '3201', 'name' => 'Kabupaten Bandung'],
                ['id' => '3202', 'name' => 'Kabupaten Bandung Barat'],
                ['id' => '3203', 'name' => 'Kabupaten Bekasi'],
                ['id' => '3204', 'name' => 'Kabupaten Bogor'],
                ['id' => '3205', 'name' => 'Kabupaten Ciamis'],
                ['id' => '3206', 'name' => 'Kabupaten Cianjur'],
                ['id' => '3207', 'name' => 'Kabupaten Cirebon'],
                ['id' => '3208', 'name' => 'Kabupaten GARUT'],
                ['id' => '3209', 'name' => 'Kabupaten Indramayu'],
                ['id' => '3210', 'name' => 'Kabupaten Karawang'],
                ['id' => '3211', 'name' => 'Kabupaten Kuningan'],
                ['id' => '3212', 'name' => 'Kabupaten Majalengka'],
                ['id' => '3213', 'name' => 'Kabupaten Pangandaran'],
                ['id' => '3214', 'name' => 'Kabupaten Purwakarta'],
                ['id' => '3215', 'name' => 'Kabupaten Subang'],
                ['id' => '3216', 'name' => 'Kabupaten Sukabumi'],
                ['id' => '3217', 'name' => 'Kabupaten Sumedang'],
                ['id' => '3218', 'name' => 'Kabupaten Tasikmalaya'],
                ['id' => '3271', 'name' => 'Kota Bandung'],
                ['id' => '3272', 'name' => 'Kota Bekasi'],
                ['id' => '3273', 'name' => 'Kota Bogor'],
                ['id' => '3274', 'name' => 'Kota Cimahi'],
                ['id' => '3275', 'name' => 'Kota Cirebon'],
                ['id' => '3276', 'name' => 'Kota DepoKota'],
                ['id' => '3277', 'name' => 'Kota Sukabumi'],
                ['id' => '3278', 'name' => 'Kota Tasikmalaya'],
            ],
            '33' => [
                ['id' => '3301', 'name' => 'Kabupaten Banjarnegara'],
                ['id' => '3302', 'name' => 'Kabupaten Banyumas'],
                ['id' => '3303', 'name' => 'Kabupaten Batang'],
                ['id' => '3304', 'name' => 'Kabupaten Blora'],
                ['id' => '3305', 'name' => 'Kabupaten Boyolali'],
                ['id' => '3306', 'name' => 'Kabupaten Brebes'],
                ['id' => '3307', 'name' => 'Kabupaten Cilacap'],
                ['id' => '3308', 'name' => 'Kabupaten Demak'],
                ['id' => '3309', 'name' => 'Kabupaten Grobogan'],
                ['id' => '3310', 'name' => 'Kabupaten Jepara'],
                ['id' => '3311', 'name' => 'Kabupaten Karanganyar'],
                ['id' => '3312', 'name' => 'Kabupaten Kebumen'],
                ['id' => '3313', 'name' => 'Kabupaten Kendal'],
                ['id' => '3314', 'name' => 'Kabupaten Klaten'],
                ['id' => '3315', 'name' => 'Kabupaten Kudus'],
                ['id' => '3316', 'name' => 'Kabupaten Magelang'],
                ['id' => '3317', 'name' => 'Kabupaten Pati'],
                ['id' => '3318', 'name' => 'Kabupaten Pekalongan'],
                ['id' => '3319', 'name' => 'Kabupaten Pemalang'],
                ['id' => '3320', 'name' => 'Kabupaten Purbalingga'],
                ['id' => '3321', 'name' => 'Kabupaten Purworejo'],
                ['id' => '3322', 'name' => 'Kabupaten Rembang'],
                ['id' => '3323', 'name' => 'Kabupaten Semarang'],
                ['id' => '3324', 'name' => 'Kabupaten Sragen'],
                ['id' => '3325', 'name' => 'Kabupaten Sukoharjo'],
                ['id' => '3326', 'name' => 'Kabupaten Temanggung'],
                ['id' => '3327', 'name' => 'Kabupaten Wonogiri'],
                ['id' => '3328', 'name' => 'Kabupaten Wonosobo'],
                ['id' => '3371', 'name' => 'Kota Magelang'],
                ['id' => '3372', 'name' => 'Kota Pekalongan'],
                ['id' => '3373', 'name' => 'Kota Salatiga'],
                ['id' => '3374', 'name' => 'Kota Semarang'],
                ['id' => '3375', 'name' => 'Kota Surakarta'],
                ['id' => '3376', 'name' => 'Kota Tegalsari'],
            ],
            '34' => [
                ['id' => '3401', 'name' => 'Kabupaten Bantul'],
                ['id' => '3402', 'name' => 'Kabupaten Gunungkidul'],
                ['id' => '3403', 'name' => 'Kabupaten Kulon Progo'],
                ['id' => '3404', 'name' => 'Kabupaten Sleman'],
                ['id' => '3471', 'name' => 'Kota Yogyakarta'],
            ],
            '35' => [
                ['id' => '3501', 'name' => 'Kabupaten Bangkalan'],
                ['id' => '3502', 'name' => 'Kabupaten Banyuwangi'],
                ['id' => '3503', 'name' => 'Kabupaten Blitar'],
                ['id' => '3504', 'name' => 'Kabupaten Bojonegoro'],
                ['id' => '3505', 'name' => 'Kabupaten Bondowoso'],
                ['id' => '3506', 'name' => 'Kabupaten Gresik'],
                ['id' => '3507', 'name' => 'Kabupaten JEMBER'],
                ['id' => '3508', 'name' => 'Kabupaten Jombang'],
                ['id' => '3509', 'name' => 'Kabupaten Kediri'],
                ['id' => '3510', 'name' => 'Kabupaten Lamongan'],
                ['id' => '3511', 'name' => 'Kabupaten Lumajang'],
                ['id' => '3512', 'name' => 'Kabupaten Madiun'],
                ['id' => '3513', 'name' => 'Kabupaten Magetan'],
                ['id' => '3514', 'name' => 'Kabupaten Malang'],
                ['id' => '3515', 'name' => 'Kabupaten Mojokerto'],
                ['id' => '3516', 'name' => 'Kabupaten Nganjuk'],
                ['id' => '3517', 'name' => 'Kabupaten Ngawi'],
                ['id' => '3518', 'name' => 'Kabupaten Pacitan'],
                ['id' => '3519', 'name' => 'Kabupaten Pamekasan'],
                ['id' => '3520', 'name' => 'Kabupaten Pasuruan'],
                ['id' => '3521', 'name' => 'Kabupaten Ponorogo'],
                ['id' => '3522', 'name' => 'Kabupaten Probolinggo'],
                ['id' => '3523', 'name' => 'Kabupaten Sampang'],
                ['id' => '3524', 'name' => 'Kabupaten Situbondo'],
                ['id' => '3525', 'name' => 'Kabupaten Sleman'],
                ['id' => '3526', 'name' => 'Kabupaten Trenggalek'],
                ['id' => '3527', 'name' => 'Kabupaten Tuban'],
                ['id' => '3528', 'name' => 'Kabupaten Tulungagung'],
                ['id' => '3571', 'name' => 'Kota Batu'],
                ['id' => '3572', 'name' => 'Kota Blitar'],
                ['id' => '3573', 'name' => 'Kota Kediri'],
                ['id' => '3574', 'name' => 'Kota Madiun'],
                ['id' => '3575', 'name' => 'Kota Malang'],
                ['id' => '3576', 'name' => 'Kota Mojokerto'],
                ['id' => '3577', 'name' => 'Kota Pasuruan'],
                ['id' => '3578', 'name' => 'Kota Probolinggo'],
                ['id' => '3579', 'name' => 'Kota Surabaya'],
            ],
            '36' => [
                ['id' => '3601', 'name' => 'Kabupaten Lebak'],
                ['id' => '3602', 'name' => 'Kabupaten Pandeglang'],
                ['id' => '3603', 'name' => 'Kabupaten Serang'],
                ['id' => '3604', 'name' => 'Kabupaten Tangerang'],
                ['id' => '3671', 'name' => 'Kota Cilegon'],
                ['id' => '3672', 'name' => 'Kota Serang'],
                ['id' => '3673', 'name' => 'Kota Tangerang'],
                ['id' => '3674', 'name' => 'Kota Tangerangs Selatan'],
            ],
            '51' => [
                ['id' => '5101', 'name' => 'Kabupaten Badung'],
                ['id' => '5102', 'name' => 'Kabupaten Bangli'],
                ['id' => '5103', 'name' => 'Kabupaten Buleleng'],
                ['id' => '5104', 'name' => 'Kabupaten Gianyar'],
                ['id' => '5105', 'name' => 'Kabupaten Jembrana'],
                ['id' => '5106', 'name' => 'Kabupaten Karangasem'],
                ['id' => '5107', 'name' => 'Kabupaten Klungkung'],
                ['id' => '5108', 'name' => 'Kabupaten Tabanan'],
                ['id' => '5171', 'name' => 'Kota Denpasar'],
            ],
            '12' => [
                ['id' => '1201', 'name' => 'Kabupaten Asahan'],
                ['id' => '1202', 'name' => 'Kabupaten Batubara'],
                ['id' => '1203', 'name' => 'Kabupaten Dairi'],
                ['id' => '1204', 'name' => 'Kabupaten Deli Serdang'],
                ['id' => '1205', 'name' => 'Kabupaten Humbang Hasundutan'],
                ['id' => '1206', 'name' => 'Kabupaten Karo'],
                ['id' => '1207', 'name' => 'Kabupaten Labuhanbatu'],
                ['id' => '1208', 'name' => 'Kabupaten Labuhanbatu Utara'],
                ['id' => '1209', 'name' => 'Kabupaten Labuhanbatu Selatan'],
                ['id' => '1210', 'name' => 'Kabupaten Langkat'],
                ['id' => '1211', 'name' => 'Kabupaten Mandailing Natal'],
                ['id' => '1212', 'name' => 'Kabupaten Nias'],
                ['id' => '1213', 'name' => 'Kabupaten Nias Barat'],
                ['id' => '1214', 'name' => 'Kabupaten Nias Selatan'],
                ['id' => '1215', 'name' => 'Kabupaten Nias Utara'],
                ['id' => '1216', 'name' => 'Kabupaten Padangsidimpuan'],
                ['id' => '1217', 'name' => 'Kabupaten Pakpak Bharat'],
                ['id' => '1218', 'name' => 'Kabupaten Samosir'],
                ['id' => '1219', 'name' => 'Kabupaten Serdang Bedagai'],
                ['id' => '1220', 'name' => 'Kabupaten Simalungun'],
                ['id' => '1221', 'name' => 'Kabupaten Tapanuli Selatan'],
                ['id' => '1222', 'name' => 'Kabupaten Tapanuli Tengah'],
                ['id' => '1223', 'name' => 'Kabupaten Tapanuli Utara'],
                ['id' => '1224', 'name' => 'Kabupaten Toba'],
                ['id' => '1271', 'name' => 'Kota Binjai'],
                ['id' => '1272', 'name' => 'Kota Gunungsitoli'],
                ['id' => '1273', 'name' => 'Kota Medan'],
                ['id' => '1274', 'name' => 'Kota Padangsidimpuan'],
                ['id' => '1275', 'name' => 'Kota Pematangsiantar'],
                ['id' => '1276', 'name' => 'Kota Sibolga'],
                ['id' => '1277', 'name' => 'Kota Tanjungbalai'],
                ['id' => '1278', 'name' => 'Kota Tebing Tinggi'],
            ],
            '13' => [
                ['id' => '1301', 'name' => 'Kabupaten Agam'],
                ['id' => '1302', 'name' => 'Kabupaten Dharmasraya'],
                ['id' => '1303', 'name' => 'Kabupaten Kepulauan Mentawai'],
                ['id' => '1304', 'name' => 'Kabupaten Lima Puluh Kota'],
                ['id' => '1305', 'name' => 'Kabupaten Padang Pariaman'],
                ['id' => '1306', 'name' => 'Kabupaten Pasaman'],
                ['id' => '1307', 'name' => 'Kabupaten Pasaman Barat'],
                ['id' => '1308', 'name' => 'Kabupaten Pesisir Selatan'],
                ['id' => '1309', 'name' => 'Kabupaten Solok'],
                ['id' => '1310', 'name' => 'Kabupaten Solok Selatan'],
                ['id' => '1311', 'name' => 'Kabupaten Tanah Datar'],
                ['id' => '1371', 'name' => 'Kota Bukittinggi'],
                ['id' => '1372', 'name' => 'Kota Padang'],
                ['id' => '1373', 'name' => 'Kota Pariaman'],
                ['id' => '1374', 'name' => 'Kota Payakumbuh'],
                ['id' => '1375', 'name' => 'Kota Solok'],
            ],
            '14' => [
                ['id' => '1401', 'name' => 'Kabupaten Indragiri Hilir'],
                ['id' => '1402', 'name' => 'Kabupaten Indragiri Hulu'],
                ['id' => '1403', 'name' => 'Kabupaten Kampar'],
                ['id' => '1404', 'name' => 'Kabupaten Kuantan Singingi'],
                ['id' => '1405', 'name' => 'Kabupaten Meranti'],
                ['id' => '1406', 'name' => 'Kabupaten Pelalawan'],
                ['id' => '1407', 'name' => 'Kabupaten Rokan Hilir'],
                ['id' => '1408', 'name' => 'Kabupaten Rokan Hulu'],
                ['id' => '1409', 'name' => 'Kabupaten Siak'],
                ['id' => '1410', 'name' => 'Kabupaten Tembilahan'],
                ['id' => '1471', 'name' => 'Kota Dumai'],
                ['id' => '1472', 'name' => 'Kota Pekanbaru'],
            ],
            '15' => [
                ['id' => '1501', 'name' => 'Kabupaten Batang Hari'],
                ['id' => '1502', 'name' => 'Kabupaten Bungo'],
                ['id' => '1503', 'name' => 'Kabupaten Jambi'],
                ['id' => '1504', 'name' => 'Kabupaten Kerinci'],
                ['id' => '1505', 'name' => 'Kabupaten Merangin'],
                ['id' => '1506', 'name' => 'Kabupaten Muaro Jambi'],
                ['id' => '1507', 'name' => 'Kabupaten Sarolangun'],
                ['id' => '1508', 'name' => 'Kabupaten Tanjung Jabung Barat'],
                ['id' => '1509', 'name' => 'Kabupaten Tanjung Jabung Timur'],
                ['id' => '1510', 'name' => 'Kabupaten Tebo'],
                ['id' => '1571', 'name' => 'Kota Jambi'],
                ['id' => '1572', 'name' => 'Kota Sungai Penuh'],
            ],
            '16' => [
                ['id' => '1601', 'name' => 'Kabupaten Banyuasin'],
                ['id' => '1602', 'name' => 'Kabupaten Empat Lawang'],
                ['id' => '1603', 'name' => 'Kabupaten Lahat'],
                ['id' => '1604', 'name' => 'Kabupaten Muara Enim'],
                ['id' => '1605', 'name' => 'Kabupaten Musi Banyuasin'],
                ['id' => '1606', 'name' => 'Kabupaten Musi Rawas'],
                ['id' => '1607', 'name' => 'Kabupaten Musi Rawas Utara'],
                ['id' => '1608', 'name' => 'Kabupaten Ogan Ilir'],
                ['id' => '1609', 'name' => 'Kabupaten Ogan Komering Ilir'],
                ['id' => '1610', 'name' => 'Kabupaten Ogan Komering Ulu'],
                ['id' => '1611', 'name' => 'Kabupaten Ogan Komering Ulu Selatan'],
                ['id' => '1612', 'name' => 'Kabupaten Ogan Komering Ulu Timur'],
                ['id' => '1613', 'name' => 'Kabupaten Penukal Abab Lematang Ilir'],
                ['id' => '1671', 'name' => 'Kota Lubuklinggau'],
                ['id' => '1672', 'name' => 'Kota Pagar Alam'],
                ['id' => '1673', 'name' => 'Kota Palembang'],
                ['id' => '1674', 'name' => 'Kota Prabumulih'],
            ],
            '17' => [
                ['id' => '1701', 'name' => 'Kabupaten Bengkulu Selatan'],
                ['id' => '1702', 'name' => 'Kabupaten Bengkulu Tengah'],
                ['id' => '1703', 'name' => 'Kabupaten Bengkulu Utara'],
                ['id' => '1704', 'name' => 'Kabupaten Kaur'],
                ['id' => '1705', 'name' => 'Kabupaten Kepahiang'],
                ['id' => '1706', 'name' => 'Kabupaten Lebong'],
                ['id' => '1707', 'name' => 'Kabupaten Mukomuko'],
                ['id' => '1708', 'name' => 'Kabupaten Rejang Lebong'],
                ['id' => '1709', 'name' => 'Kabupaten Seluma'],
                ['id' => '1771', 'name' => 'Kota Bengkulu'],
            ],
            '18' => [
                ['id' => '1801', 'name' => 'Kabupaten Bandar Lampung'],
                ['id' => '1802', 'name' => 'Kabupaten Barat'],
                ['id' => '1803', 'name' => 'Kabupaten Greenville'],
                ['id' => '1804', 'name' => 'Kabupaten Lampung Selatan'],
                ['id' => '1805', 'name' => 'Kabupaten Lampung Tengah'],
                ['id' => '1806', 'name' => 'Kabupaten Lampung Timur'],
                ['id' => '1807', 'name' => 'Kabupaten Lampung Utara'],
                ['id' => '1808', 'name' => 'Kabupaten Mesuji'],
                ['id' => '1809', 'name' => 'Kabupaten Pesawaran'],
                ['id' => '1810', 'name' => 'Kabupaten Pringsewu'],
                ['id' => '1811', 'name' => 'Kabupaten Tanggamus'],
                ['id' => '1812', 'name' => 'Kabupaten Tulang Bawang'],
                ['id' => '1813', 'name' => 'Kabupaten Tulang Bawang Barat'],
                ['id' => '1814', 'name' => 'Kabupaten Way Kanan'],
                ['id' => '1871', 'name' => 'Kota Bandar Lampung'],
                ['id' => '1872', 'name' => 'Kota Metro'],
            ],
            '19' => [
                ['id' => '1901', 'name' => 'Kabupaten Bangka'],
                ['id' => '1902', 'name' => 'Kabupaten Bangka Barat'],
                ['id' => '1903', 'name' => 'Kabupaten Bangka Selatan'],
                ['id' => '1904', 'name' => 'Kabupaten Bangka Tengah'],
                ['id' => '1905', 'name' => 'Kabupaten Belitung'],
                ['id' => '1906', 'name' => 'Kabupaten Belitung Timur'],
                ['id' => '1971', 'name' => 'Kota Pangkal Pinang'],
            ],
            '21' => [
                ['id' => '2101', 'name' => 'Kabupaten Bintan'],
                ['id' => '2102', 'name' => 'Kabupaten Karimun'],
                ['id' => '2103', 'name' => 'Kabupaten Kepulauan Anambas'],
                ['id' => '2104', 'name' => 'Kabupaten Lingga'],
                ['id' => '2105', 'name' => 'Kabupaten Natuna'],
                ['id' => '2171', 'name' => 'Kota Batamm'],
                ['id' => '2172', 'name' => 'Kota Tanjung Pinang'],
            ],
            '52' => [
                ['id' => '5201', 'name' => 'Kabupaten Bima'],
                ['id' => '5202', 'name' => 'Kabupaten Dompu'],
                ['id' => '5203', 'name' => 'Kabupaten Lombok Barat'],
                ['id' => '5204', 'name' => 'Kabupaten Lombok Tengah'],
                ['id' => '5205', 'name' => 'Kabupaten Lombok Timur'],
                ['id' => '5206', 'name' => 'Kabupaten Lombok Utara'],
                ['id' => '5207', 'name' => 'Kabupaten Sumbawa'],
                ['id' => '5208', 'name' => 'Kabupaten Sumbawa Barat'],
                ['id' => '5271', 'name' => 'Kota Bima'],
                ['id' => '5272', 'name' => 'Kota Mataram'],
            ],
            '53' => [
                ['id' => '5301', 'name' => 'Kabupaten Alor'],
                ['id' => '5302', 'name' => 'Kabupaten Belu'],
                ['id' => '5303', 'name' => 'Kabupaten Ende'],
                ['id' => '5304', 'name' => 'Kabupaten Flores Timur'],
                ['id' => '5305', 'name' => 'Kabupaten Sumba Barat'],
                ['id' => '5306', 'name' => 'Kabupaten Sumba Barat Daya'],
                ['id' => '5307', 'name' => 'Kabupaten Sumba Tengah'],
                ['id' => '5308', 'name' => 'Kabupaten Sumba Timur'],
                ['id' => '5309', 'name' => 'Kabupaten Timor Tengah Selatan'],
                ['id' => '5310', 'name' => 'Kabupaten Timor Tengah Utara'],
                ['id' => '5311', 'name' => 'Kabupaten Lembata'],
                ['id' => '5312', 'name' => 'Kabupaten Manggarai'],
                ['id' => '5313', 'name' => 'Kabupaten Manggarai Barat'],
                ['id' => '5314', 'name' => 'Kabupaten Manggarai Timur'],
                ['id' => '5315', 'name' => 'Kabupaten Ngada'],
                ['id' => '5316', 'name' => 'Kabupaten Nagekeo'],
                ['id' => '5317', 'name' => 'Kabupaten Rote Ndao'],
                ['id' => '5318', 'name' => 'Kabupaten Sabu Raijua'],
                ['id' => '5319', 'name' => 'Kabupaten Sikka'],
                ['id' => '5320', 'name' => 'Kabupaten Manggarai'],
                ['id' => '5371', 'name' => 'Kota Ende'],
                ['id' => '5372', 'name' => 'Kota Jakarta'],
                ['id' => '5373', 'name' => 'Kota Waingapu'],
            ],
            '61' => [
                ['id' => '6101', 'name' => 'Kabupaten Sambas'],
                ['id' => '6102', 'name' => 'Kabupaten Sanggau'],
                ['id' => '6103', 'name' => 'Kabupaten Sintang'],
                ['id' => '6104', 'name' => 'Kabupaten Kapuas Hulu'],
                ['id' => '6105', 'name' => 'Kabupaten Ketapang'],
                ['id' => '6106', 'name' => 'Kabupaten Pontianak'],
                ['id' => '6107', 'name' => 'Kabupaten Singkawang'],
                ['id' => '6108', 'name' => 'Kabupaten Melawi'],
                ['id' => '6109', 'name' => 'Kabupaten Kayong Utara'],
                ['id' => '6110', 'name' => 'Kabupaten Kubu Raya'],
                ['id' => '6171', 'name' => 'Kota Pontianak'],
                ['id' => '6172', 'name' => 'Kota Singkawang'],
            ],
            '62' => [
                ['id' => '6201', 'name' => 'Kabupaten Barito Selatan'],
                ['id' => '6202', 'name' => 'Kabupaten Barito Utara'],
                ['id' => '6203', 'name' => 'Kabupaten Barito Timur'],
                ['id' => '6204', 'name' => 'Kabupaten Barito Barat'],
                ['id' => '6205', 'name' => 'Kabupaten Gunung Mas'],
                ['id' => '6206', 'name' => 'Kabupaten Kapuas'],
                ['id' => '6207', 'name' => 'Kabupaten Katingan'],
                ['id' => '6208', 'name' => 'Kabupaten Kotawaringin Barat'],
                ['id' => '6209', 'name' => 'Kabupaten Kotawaringin Timur'],
                ['id' => '6210', 'name' => 'Kabupaten Lamandau'],
                ['id' => '6211', 'name' => 'Kabupaten Murung Raya'],
                ['id' => '6212', 'name' => 'Kabupaten Pulang Pisau'],
                ['id' => '6213', 'name' => 'Kabupaten Seruyan'],
                ['id' => '6214', 'name' => 'Kabupaten Sukamara'],
                ['id' => '6271', 'name' => 'Kota Palangka Raya'],
            ],
            '63' => [
                ['id' => '6301', 'name' => 'Kabupaten Balangan'],
                ['id' => '6302', 'name' => 'Kabupaten Banjarr'],
                ['id' => '6303', 'name' => 'Kabupaten Barito Kuala'],
                ['id' => '6304', 'name' => 'Kabupaten Hulu Sungai Selatan'],
                ['id' => '6305', 'name' => 'Kabupaten Hulu Sungai Tengah'],
                ['id' => '6306', 'name' => 'Kabupaten Hulu Sungai Utara'],
                ['id' => '6307', 'name' => 'Kabupaten Kotabaru'],
                ['id' => '6308', 'name' => 'Kabupaten Tabalong'],
                ['id' => '6309', 'name' => 'Kabupaten Tanah Bumbu'],
                ['id' => '6310', 'name' => 'Kabupaten Tanah Laut'],
                ['id' => '6311', 'name' => 'Kabupaten Tapin'],
                ['id' => '6371', 'name' => 'Kota Banjarmasin'],
                ['id' => '6372', 'name' => 'Kota Banjarbaru'],
            ],
            '64' => [
                ['id' => '6401', 'name' => 'Kabupaten Berau'],
                ['id' => '6402', 'name' => 'Kabupaten Kutai Kartanegara'],
                ['id' => '6403', 'name' => 'Kabupaten Kutai Barat'],
                ['id' => '6404', 'name' => 'Kabupaten Kutai Timur'],
                ['id' => '6405', 'name' => 'Kabupaten Paser'],
                ['id' => '6406', 'name' => 'Kabupaten Penajam Paser Utara'],
                ['id' => '6407', 'name' => 'Kabupaten Mahakam Ulu'],
                ['id' => '6471', 'name' => 'Kota Bontang'],
                ['id' => '6472', 'name' => 'Kota Samarinda'],
            ],
            '65' => [
                ['id' => '6501', 'name' => 'Kabupaten Bulungan'],
                ['id' => '6502', 'name' => 'Kabupaten Malinau'],
                ['id' => '6503', 'name' => 'Kabupaten Nunukan'],
                ['id' => '6504', 'name' => 'Kabupaten Tana Tidung'],
                ['id' => '6505', 'name' => 'Kabupaten Tarakan'],
                ['id' => '6571', 'name' => 'Kota Tarakan'],
            ],
            '71' => [
                ['id' => '7101', 'name' => 'Kabupaten Bolaang Mongondow'],
                ['id' => '7102', 'name' => 'Kabupaten Bolaang Mongondow Selatan'],
                ['id' => '7103', 'name' => 'Kabupaten Bolaang Mongondow Timur'],
                ['id' => '7104', 'name' => 'Kabupaten Bolaang Mongondow Utara'],
                ['id' => '7105', 'name' => 'Kabupaten Kepulauan Sangihe'],
                ['id' => '7106', 'name' => 'Kabupaten Kepulauan Talaud'],
                ['id' => '7107', 'name' => 'Kabupaten Minahasa'],
                ['id' => '7108', 'name' => 'Kabupaten Minahasa Selatan'],
                ['id' => '7109', 'name' => 'Kabupaten Minahasa Tenggara'],
                ['id' => '7110', 'name' => 'Kabupaten Minahasa Utara'],
                ['id' => '7111', 'name' => 'Kabupaten Sitaro'],
                ['id' => '7171', 'name' => 'Kota Bitung'],
                ['id' => '7172', 'name' => 'Kota Kotamobagu'],
                ['id' => '7173', 'name' => 'Kota Makassar'],
                ['id' => '7174', 'name' => 'Kota Tomohon'],
            ],
            '72' => [
                ['id' => '7201', 'name' => 'Kabupaten Banggai'],
                ['id' => '7202', 'name' => 'Kabupaten Banggai Kep.'],
                ['id' => '7203', 'name' => 'Kabupaten Banggai Laut'],
                ['id' => '7204', 'name' => 'Kabupaten Buol'],
                ['id' => '7205', 'name' => 'Kabupaten Donggala'],
                ['id' => '7206', 'name' => 'Kabupaten Morowali'],
                ['id' => '7207', 'name' => 'Kabupaten Parigi Moutong'],
                ['id' => '7208', 'name' => 'Kabupaten Poso'],
                ['id' => '7209', 'name' => 'Kabupaten Sigi'],
                ['id' => '7210', 'name' => 'Kabupaten Tojo Una-Una'],
                ['id' => '7211', 'name' => 'Kabupaten Toli-Toli'],
                ['id' => '7271', 'name' => 'Kota Palu'],
            ],
            '73' => [
                ['id' => '7301', 'name' => 'Kabupaten Bantaeng'],
                ['id' => '7302', 'name' => 'Kabupaten Barru'],
                ['id' => '7303', 'name' => 'Kabupaten Bone'],
                ['id' => '7304', 'name' => 'Kabupaten Bulukumba'],
                ['id' => '7305', 'name' => 'Kabupaten Enrekang'],
                ['id' => '7306', 'name' => 'Kabupaten Gowa'],
                ['id' => '7307', 'name' => 'Kabupaten Jeneponto'],
                ['id' => '7308', 'name' => 'Kabupaten Kep. Selayar'],
                ['id' => '7309', 'name' => 'Kabupaten Luwu'],
                ['id' => '7310', 'name' => 'Kabupaten Luwu Timur'],
                ['id' => '7311', 'name' => 'Kabupaten Luwu Utara'],
                ['id' => '7312', 'name' => 'Kabupaten Maros'],
                ['id' => '7313', 'name' => 'Kabupaten Pangkajene Kep.'],
                ['id' => '7314', 'name' => 'Kabupaten Pinrang'],
                ['id' => '7315', 'name' => 'Kabupaten Sidenreng Rappang'],
                ['id' => '7316', 'name' => 'Kabupaten Sinjai'],
                ['id' => '7317', 'name' => 'Kabupaten Soppeng'],
                ['id' => '7318', 'name' => 'Kabupaten Takalar'],
                ['id' => '7319', 'name' => 'Kabupaten Tana Toraja'],
                ['id' => '7320', 'name' => 'Kabupaten Toraja Utara'],
                ['id' => '7321', 'name' => 'Kabupaten Wajo'],
                ['id' => '7371', 'name' => 'Kota Makassar'],
                ['id' => '7372', 'name' => 'Kota Parepare'],
                ['id' => '7373', 'name' => 'Kota Palopo'],
            ],
            '74' => [
                ['id' => '7401', 'name' => 'Kabupaten Bombana'],
                ['id' => '7402', 'name' => 'Kabupaten Buton'],
                ['id' => '7403', 'name' => 'Kabupaten Buton Selatan'],
                ['id' => '7404', 'name' => 'Kabupaten Buton Tengah'],
                ['id' => '7405', 'name' => 'Kabupaten Buton Utara'],
                ['id' => '7406', 'name' => 'Kabupaten Kolaka'],
                ['id' => '7407', 'name' => 'Kabupaten Kolaka Timur'],
                ['id' => '7408', 'name' => 'Kabupaten Kolaka Utara'],
                ['id' => '7409', 'name' => 'Kabupaten Konawe'],
                ['id' => '7410', 'name' => 'Kabupaten Konawe Selatan'],
                ['id' => '7411', 'name' => 'Kabupaten Konawe Utara'],
                ['id' => '7412', 'name' => 'Kabupaten Muna'],
                ['id' => '7413', 'name' => 'Kabupaten Muna Barat'],
                ['id' => '7414', 'name' => 'Kabupaten Wakatobi'],
                ['id' => '7471', 'name' => 'Kota Bau-Bau'],
                ['id' => '7472', 'name' => 'Kota Kendari'],
            ],
            '75' => [
                ['id' => '7501', 'name' => 'Kabupaten Boalemo'],
                ['id' => '7502', 'name' => 'Kabupaten Bone Bolango'],
                ['id' => '7503', 'name' => 'Kabupaten Gorontalo'],
                ['id' => '7504', 'name' => 'Kabupaten Gorontalo Utara'],
                ['id' => '7505', 'name' => 'Kabupaten Pohuwato'],
                ['id' => '7571', 'name' => 'Kota Gorontalo'],
            ],
            '76' => [
                ['id' => '7601', 'name' => 'Kabupaten Majene'],
                ['id' => '7602', 'name' => 'Kabupaten Mamasa'],
                ['id' => '7603', 'name' => 'Kabupaten Mamuju'],
                ['id' => '7604', 'name' => 'Kabupaten Mamuju Tengah'],
                ['id' => '7605', 'name' => 'Kabupaten Mamuju Utara'],
                ['id' => '7606', 'name' => 'Kabupaten Polewali Mandar'],
            ],
            '81' => [
                ['id' => '8101', 'name' => 'Kabupaten Buru'],
                ['id' => '8102', 'name' => 'Kabupaten Buru Selatan'],
                ['id' => '8103', 'name' => 'Kabupaten Maluku Barat Daya'],
                ['id' => '8104', 'name' => 'Kabupaten Maluku Tengah'],
                ['id' => '8105', 'name' => 'Kabupaten Maluku Tenggara'],
                ['id' => '8106', 'name' => 'Kabupaten Maluku Tenggara Barat'],
                ['id' => '8107', 'name' => 'Kabupaten Seram Bagian Barat'],
                ['id' => '8108', 'name' => 'Kabupaten Seram Bagian Timur'],
                ['id' => '8171', 'name' => 'Kota Ambon'],
                ['id' => '8172', 'name' => 'Kota Tual'],
            ],
            '82' => [
                ['id' => '8201', 'name' => 'Kabupaten Halmahera Barat'],
                ['id' => '8202', 'name' => 'Kabupaten Halmahera Tengah'],
                ['id' => '8203', 'name' => 'Kabupaten Halmahera Utara'],
                ['id' => '8204', 'name' => 'Kabupaten Halmahera Selatan'],
                ['id' => '8205', 'name' => 'Kabupaten Kep. Sula'],
                ['id' => '8206', 'name' => 'Kabupaten Maluku Utara'],
                ['id' => '8207', 'name' => 'Kabupaten Obi Island'],
                ['id' => '8271', 'name' => 'Kota Ternate'],
                ['id' => '8272', 'name' => 'Kota Tidore Kepulauan'],
            ],
            '91' => [
                ['id' => '9101', 'name' => 'Kabupaten Fakfak'],
                ['id' => '9102', 'name' => 'Kabupaten Kaimana'],
                ['id' => '9103', 'name' => 'Kabupaten Manokwari'],
                ['id' => '9104', 'name' => 'Kabupaten Manokwari Selatan'],
                ['id' => '9105', 'name' => 'Kabupaten Maybrat'],
                ['id' => '9106', 'name' => 'Kabupaten Pegunungan Arfak'],
                ['id' => '9107', 'name' => 'Kabupaten Raja Ampat'],
                ['id' => '9108', 'name' => 'Kabupaten Sorong'],
                ['id' => '9109', 'name' => 'Kabupaten Sorong Selatan'],
                ['id' => '9110', 'name' => 'Kabupaten Tambrauw'],
                ['id' => '9111', 'name' => 'Kabupaten Wah Utara'],
                ['id' => '9171', 'name' => 'Kota Sorong'],
            ],
            '94' => [
                ['id' => '9401', 'name' => 'Kabupaten Biak Numfor'],
                ['id' => '9402', 'name' => 'Kabupaten Bovendigoel'],
                ['id' => '9403', 'name' => 'Kabupaten Deiyai'],
                ['id' => '9404', 'name' => 'Kabupaten Dogiyai'],
                ['id' => '9405', 'name' => 'Kabupaten Intan Jaya'],
                ['id' => '9406', 'name' => 'Kabupaten Jayawijaya'],
                ['id' => '9407', 'name' => 'Kabupaten Keerom'],
                ['id' => '9408', 'name' => 'Kabupaten Kep. Yapen'],
                ['id' => '9409', 'name' => 'Kabupaten Lanny Jaya'],
                ['id' => '9410', 'name' => 'Kabupaten Mamberamo Raya'],
                ['id' => '9411', 'name' => 'Kabupaten Mamberamo Tengah'],
                ['id' => '9412', 'name' => 'Kabupaten Nabire'],
                ['id' => '9413', 'name' => 'Kabupaten Nduga'],
                ['id' => '9414', 'name' => 'Kabupaten Paniai'],
                ['id' => '9415', 'name' => 'Kabupaten Pardosi'],
                ['id' => '9416', 'name' => 'Kabupaten Puncak'],
                ['id' => '9417', 'name' => 'Kabupaten Puncak Jaya'],
                ['id' => '9418', 'name' => 'Kabupaten Sarmi'],
                ['id' => '9419', 'name' => 'Kabupaten Supiori'],
                ['id' => '9420', 'name' => 'Kabupaten Tolikara'],
                ['id' => '9421', 'name' => 'Kabupaten Waropen'],
                ['id' => '9422', 'name' => 'Kabupaten Yahukimo'],
                ['id' => '9423', 'name' => 'Kabupaten Yalimo'],
                ['id' => '9471', 'name' => 'Kota Jayawijaya'],
                ['id' => '9472', 'name' => 'Kota Nabire'],
            ],
            '95' => [
                ['id' => '9501', 'name' => 'Kabupaten Asmat'],
                ['id' => '9502', 'name' => 'Kabupaten Boven Digoel'],
                ['id' => '9503', 'name' => 'Kabupaten Mappi'],
                ['id' => '9504', 'name' => 'Kabupaten Merauke'],
                ['id' => '9505', 'name' => 'Kabupaten Mimika'],
                ['id' => '9506', 'name' => 'Kabupaten Nabire'],
                ['id' => '9507', 'name' => 'Kabupaten Paniai'],
                ['id' => '9508', 'name' => 'Kabupaten Puncak Jaya'],
                ['id' => '9509', 'name' => 'Kabupaten Sarmi'],
                ['id' => '9510', 'name' => 'Kabupaten Supiori'],
                ['id' => '9511', 'name' => 'Kabupaten Waropen'],
            ],
            '96' => [
                ['id' => '9601', 'name' => 'Kabupaten Deiyai'],
                ['id' => '9602', 'name' => 'Kabupaten Dogiyai'],
                ['id' => '9603', 'name' => 'Kabupaten Intan Jaya'],
                ['id' => '9604', 'name' => 'Kabupaten Jayawijaya'],
                ['id' => '9605', 'name' => 'Kabupaten Lanny Jaya'],
                ['id' => '9606', 'name' => 'Kabupaten Mamberamo Tengah'],
                ['id' => '9607', 'name' => 'Kabupaten Nabire'],
                ['id' => '9608', 'name' => 'Kabupaten Nduga'],
                ['id' => '9609', 'name' => 'Kabupaten Paniai'],
                ['id' => '9610', 'name' => 'Kabupaten Puncak'],
                ['id' => '9611', 'name' => 'Kabupaten Tolikara'],
                ['id' => '9612', 'name' => 'Kabupaten Yahukimo'],
                ['id' => '9613', 'name' => 'Kabupaten Yalimo'],
            ],
            '97' => [
                ['id' => '9701', 'name' => 'Kabupaten Jayawijaya'],
                ['id' => '9702', 'name' => 'Kabupaten Lanny Jaya'],
                ['id' => '9703', 'name' => 'Kabupaten Mamberamo Tengah'],
                ['id' => '9704', 'name' => 'Kabupaten Nduga'],
                ['id' => '9705', 'name' => 'Kabupaten Puncak'],
                ['id' => '9706', 'name' => 'Kabupaten Tolikara'],
                ['id' => '9707', 'name' => 'Kabupaten Yahukimo'],
                ['id' => '9708', 'name' => 'Kabupaten Yalimo'],
            ],
        ];
        
        if (isset($regencies[$provinceId])) {
            return response()->json(['regencies' => $regencies[$provinceId]]);
        }
        
        return response()->json(['regencies' => []]);
    }
}
