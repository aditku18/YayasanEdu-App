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
use Illuminate\Support\Facades\DB;

class FoundationRegistrationControllerNew extends Controller
{
    protected $steps = ['institution', 'package', 'admin', 'confirmation']; // Simplified to 4 steps
    
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
        if ($request->has('plan') && empty($data['step2'])) {
            $preSelectedPlan = Plan::find($request->plan);
            if ($preSelectedPlan) {
                $data['step2'] = [
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
     * Display step 2: Package Selection (Simplified)
     */
    public function step2()
    {
        $data = session('registration_data', []);
        
        if (empty($data)) {
            $data = [
                'step1' => ['foundation_name' => 'Test Foundation'],
                'step2' => ['plan_id' => 1, 'plan' => ['price_per_month' => 0]],
                'step3' => []
            ];
            session(['registration_data' => $data]);
        }
        
        // Get plans with included plugins
        $plans = Plan::with('includedPlugins')->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
        
        return view('register-foundation', [
            'step' => 2,
            'stepName' => 'Pilih Paket',
            'data' => $data,
            'plans' => $plans,
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process step 2: Save selected package with additional plugins
     */
    public function postStep2(Request $request)
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
        $data['step2'] = [
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

        return redirect()->route('register.foundation.step3');
    }

    /**
     * Display step 3: Admin Account
     */
    public function step3()
    {
        // Bypass session check for testing
        $data = session('registration_data', []);
        
        // Set session data for testing if empty
        if (empty($data)) {
            $data = [
                'step1' => ['foundation_name' => 'Test Foundation'],
                'step2' => [
                    'plan_id' => 1, 
                    'plan' => [
                        'id' => 1,
                        'name' => 'Gratis',
                        'price_per_month' => 0,
                        'included_plugins' => [],
                        'plugin_slots' => 0
                    ]
                ],
                'step3' => []
            ];
            session(['registration_data' => $data]);
        }
        
        return view('register-foundation', [
            'step' => 3,
            'stepName' => 'Akun Admin',
            'data' => $data,
            'plans' => $this->getPlans(),
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process step 3: Validate and store admin data
     */
    public function postStep3(Request $request)
    {
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

        $data = session('registration_data', []);
        $data['step3'] = [
            'admin_name' => $validated['admin_name'],
            'admin_email' => $validated['admin_email'],
            'admin_phone' => $validated['admin_phone'],
            'password' => $validated['password'],
        ];
        session(['registration_data' => $data]);

        return redirect()->route('register.foundation.step4');
    }

    /**
     * Display step 4: Confirmation
     */
    public function step4()
    {
        // Bypass session check for testing
        $data = session('registration_data', []);
        
        // Debug: Log session data
        \Log::info('Step 4 - Session data:', $data);
        
        if (empty($data)) {
            return redirect()->route('register.foundation.step1');
        }
        
        // Recalculate plugin prices from database to ensure accuracy
        if (!empty($data['step2']['additional_plugins'])) {
            $pluginIds = $data['step2']['additional_plugins'];
            $freshPlugins = Plugin::whereIn('id', $pluginIds)->get(['id', 'name', 'price']);
            $data['step2']['plugins'] = $freshPlugins->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'formatted_price' => 'Rp' . number_format($p->price, 0, ',', '.')
                ];
            })->toArray();
            $data['step2']['plugin_total'] = $freshPlugins->sum('price');
        }
        
        return view('register-foundation', [
            'step' => 4,
            'stepName' => 'Konfirmasi',
            'data' => $data,
            'plans' => $this->getPlans(),
            'plugins' => $this->getPlugins(),
            'provinces' => $this->getProvinces(),
            'regencies' => []
        ]);
    }

    /**
     * Process final submission
     */
    public function postStep4(Request $request)
    {
        $validated = $request->validate([
            'terms' => 'required|accepted',
            'data_accuracy' => 'required|accepted',
            'newsletter' => 'nullable',
        ], [
            'terms.required' => 'Anda harus menyetujui Syarat dan Ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui Syarat dan Ketentuan.',
            'data_accuracy.required' => 'Anda harus menyatakan keakuratan data.',
            'data_accuracy.accepted' => 'Anda harus menyatakan keakuratan data.',
        ]);

        // Create foundation and user
        $registrationData = session('registration_data');
        
        // Debug: Log registration data
        \Log::info('Registration data:', $registrationData ?? []);
        
        try {
            DB::beginTransaction();
            
            // Check if email already exists before creating user
            $existingUser = User::where('email', $registrationData['step3']['admin_email'])->first();
            if ($existingUser) {
                // Check if there's an incomplete foundation associated with this user
                $incompleteFoundation = Foundation::where('email', $registrationData['step3']['admin_email'])
                    ->where('status', 'pending')
                    ->first();
                
                if ($incompleteFoundation) {
                    // Clean up incomplete foundation
                    $incompleteFoundation->delete();
                    \Log::info('Cleaned up incomplete foundation for email: ' . $registrationData['step3']['admin_email']);
                }
                
                return back()->withErrors(['error' => 'Email ' . $registrationData['step3']['admin_email'] . ' sudah terdaftar. Silakan gunakan email lain atau coba reset password.'])->withInput();
            }
            
            // Create user first
            $user = User::create([
                'name' => $registrationData['step3']['admin_name'],
                'email' => $registrationData['step3']['admin_email'],
                'password' => Hash::make($registrationData['step3']['password']),
                'tenant_id' => null,
                'role' => 'foundation_admin',
                'is_active' => true,
            ]);

            // Create foundation
            // Generate subdomain from foundation name
            $subdomain = $this->generateSubdomain($registrationData['step1']['foundation_name']);
            
            // Get plan first to determine trial duration
            $plan = Plan::find($registrationData['step2']['plan_id']);
            
            $foundation = Foundation::create([
                'tenant_id' => null,
                'name' => $registrationData['step1']['foundation_name'],
                'email' => $registrationData['step1']['email'],
                'phone' => $registrationData['step1']['phone'],
                'address' => $registrationData['step1']['address'],
                'province' => $registrationData['step1']['province'],
                'regency' => $registrationData['step1']['regency'],
                'npsn' => $registrationData['step1']['npsn'] ?? null,
                'institution_type' => $registrationData['step1']['institution_type'],
                'education_levels' => json_encode($registrationData['step1']['education_levels']),
                'student_count' => $registrationData['step1']['student_count'],
                'website' => $registrationData['step1']['website'] ?? null,
                'subdomain' => $subdomain,
                'status' => 'trial',
                'plan_id' => $registrationData['step2']['plan_id'],
                'admin_user_id' => $user->id,
                'trial_ends_at' => now()->addDays($plan->duration_days ?? 14),
            ]);

            // Create subscription
            $subscription = Subscription::create([
                'foundation_id' => $foundation->id,
                'plan_id' => $plan->id,
                'status' => 'trial',
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days ?? 14),
                'price' => $plan->price_per_month,
                'billing_cycle' => 'monthly',
            ]);

            // 🚀 NEW: Auto-install plugins based on plan
            $pluginService = new PluginInstallationService();
            $success = $pluginService->installPackagePlugins($foundation, $plan);
            
            if (!$success) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Gagal menginstall plugin. Silakan coba lagi.'])->withInput();
            }

            // Update foundation with plugin info
            $foundation->update([
                'included_plugins' => $plan->included_plugins ?? [],
                'additional_plugins' => $registrationData['step2']['additional_plugins'] ?? [],
                'plugin_slots' => $plan->plugin_slots ?? 0,
                'plugins_installed_at' => now(),
            ]);

            // Clear session
            session()->forget('registration_data');

            // Send verification email
            event(new Registered($user));

            // Do NOT auto-login — user must verify email first
            // Auth::login($user);

            return redirect()->route('register.foundation.success');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Success page
     */
    public function success()
    {
        return view('registration-success');
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
    private function processDocumentUploads($step1Data, $foundation)
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
            if (isset($step1Data[$sessionKey]) && Storage::disk('public')->exists($step1Data[$sessionKey])) {
                $fileName = $diskPath . '.' . pathinfo($step1Data[$sessionKey], PATHINFO_EXTENSION);
                Storage::disk('public')->move($step1Data[$sessionKey], $uploadPath . '/' . $fileName);
                
                // Update foundation with document path
                $foundation->update([$sessionKey . '_path' => $uploadPath . '/' . $fileName]);
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
                ['id' => '3208', 'name' => 'Kabupaten Garut'],
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
                ['id' => '3276', 'name' => 'Kota Depok'],
                ['id' => '3277', 'name' => 'Kota Sukabumi'],
                ['id' => '3278', 'name' => 'Kota Tasikmalaya'],
            ],
        ];

        return response()->json($regencies[$provinceId] ?? []);
    }
}
