<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ValidationException as ValException;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        $credentials['email'] = strtolower($credentials['email']);

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            Log::info("Login failed - Auth::attempt returned false for: " . $this->email);

            throw ValException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        
        // In a stancl/tenancy multi-database setup, users in the tenant DB 
        // will not have a tenant_id column (or it will be null) because the entire 
        // DB belongs to the tenant.
        // We only need to check if a central user is trying to log in directly to a tenant, 
        // or vice versa, but usually Auth::attempt handles DB scoping automatically.
        $user = Auth::user();
        if (tenant() && $user->role === 'super_admin') {
            Log::info("Login failed - Super Admin trying to login to tenant directly.");
            Auth::logout();
            throw ValException::withMessages([
                'email' => 'Silakan login melalui Platform Central.',
            ]);
        }
        if (!tenant() && in_array($user->role, ['school_admin', 'teacher', 'staff'])) {
            Log::info("Login failed - Tenant user trying to login centrally.");
            Auth::logout();
            throw ValException::withMessages([
                'email' => 'Silakan login melalui subdomain sekolah Anda.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
