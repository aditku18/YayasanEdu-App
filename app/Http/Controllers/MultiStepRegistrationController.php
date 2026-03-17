<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Foundation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class MultiStepRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show step 1: Email only (lowest barrier)
     */
    public function step1()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
            
        return view('register-multi-step', [
            'step' => 1,
            'plans' => $plans,
            'data' => session('registration_data', [])
        ]);
    }

    /**
     * Process step 1: Save email, move to step 2
     */
    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
        ]);

        // Store email in session
        $data = session('registration_data', []);
        $data['email'] = $validated['email'];
        session(['registration_data' => $data]);

        // Track event
        $this->trackEvent('registration_step1_complete', ['email' => $validated['email']]);

        return redirect()->route('register.step2');
    }

    /**
     * Show step 2: Password and Name
     */
    public function step2()
    {
        if (!session('registration_data.email')) {
            return redirect()->route('register.multi.step1');
        }

        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
            
        return view('register-multi-step', [
            'step' => 2,
            'plans' => $plans,
            'data' => session('registration_data', [])
        ]);
    }

    /**
     * Process step 2: Save password and name, move to step 3
     */
    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Store in session
        $data = session('registration_data', []);
        $data['name'] = $validated['name'];
        $data['password'] = $validated['password'];
        $data['password_confirmation'] = $request->password_confirmation;
        session(['registration_data' => $data]);

        return redirect()->route('register.step3');
    }

    /**
     * Show step 3: Foundation details and plan selection
     */
    public function step3()
    {
        if (!session('registration_data.name') || !session('registration_data.password')) {
            return redirect()->route('register.multi.step1');
        }

        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
            
        return view('register-multi-step', [
            'step' => 3,
            'plans' => $plans,
            'data' => session('registration_data', [])
        ]);
    }

    /**
     * Process step 3: Complete registration
     */
    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'foundation_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'plan_id' => 'required|exists:plans,id',
            'terms' => 'accepted',
        ], [
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            'plan_id.required' => 'Silakan pilih paket layanan.',
        ]);

        $data = session('registration_data', []);

        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tenant_id' => null,
            'role' => 'foundation_admin',
        ]);

        // Create foundation
        $plan = Plan::find($validated['plan_id']);
        Foundation::create([
            'tenant_id' => null,
            'name' => $validated['foundation_name'],
            'address' => $validated['address'],
            'email' => $data['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => 'pending',
            'plan_id' => $validated['plan_id'],
        ]);

        // Track complete registration
        $this->trackEvent('registration_complete', [
            'email' => $data['email'],
            'plan' => $plan->name
        ]);

        // Clear session
        session()->forget('registration_data');

        // Send verification email
        event(new Registered($user));

        // Auto login
        Auth::login($user);

        // Redirect to success
        return redirect()->route('registration.success');
    }

    /**
     * Skip to existing registration form
     */
    public function skip()
    {
        return redirect()->route('register.foundation');
    }

    /**
     * Clear session data
     */
    public function reset()
    {
        session()->forget('registration_data');
        return redirect()->route('register.multi.step1');
    }

    /**
     * Track analytics event (placeholder - will work with GA4)
     */
    private function trackEvent($eventName, $params = [])
    {
        // This will be captured by GA4 if installed
        if (app()->bound('session')) {
            session()->put('last_event', [
                'name' => $eventName,
                'params' => $params,
                'timestamp' => now()->toIso8601String()
            ]);
        }
    }
}
