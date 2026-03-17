<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Foundation;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationController extends Controller
{
    /**
     * Tampilkan daftar yayasan beserta status verifikasi email user-nya.
     */
    public function index()
    {
        $foundations = Foundation::latest()->paginate(10);

        // Get related users by email
        $foundationEmails = $foundations->pluck('email')->toArray();
        $users = User::whereIn('email', $foundationEmails)->get()->keyBy('email');

        return view('admin.email-verifications.index', compact('foundations', 'users'));
    }

    /**
     * Verifikasi email user secara manual oleh platform admin.
     */
    public function verify(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'Email user ini sudah terverifikasi sebelumnya.');
        }

        $user->markEmailAsVerified();

        return redirect()->back()->with('success', "Email {$user->email} berhasil diverifikasi secara manual.");
    }

    /**
     * Kirim ulang email verifikasi ke user.
     */
    public function resend(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'Email user ini sudah terverifikasi.');
        }

        $user->notify(new VerifyEmail);

        return redirect()->back()->with('success', "Email verifikasi berhasil dikirim ulang ke {$user->email}.");
    }
}
