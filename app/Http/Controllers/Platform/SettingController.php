<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $defaults = [
            'site_name' => config('app.name'),
            'support_email' => config('mail.from.address', ''),
            'payment_gateway' => Setting::get('payment_gateway', 'manual'),
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
        ];

        $settings = [
            'site_name' => Setting::get('site_name', $defaults['site_name']),
            'support_email' => Setting::get('support_email', $defaults['support_email']),
            'payment_gateway' => Setting::get('payment_gateway', $defaults['payment_gateway']),
            'maintenance_mode' => Setting::get('maintenance_mode', $defaults['maintenance_mode']),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'payment_gateway' => 'nullable|string|max:100',
            'maintenance_mode' => 'nullable|in:0,1',
        ]);

        Setting::set('site_name', $data['site_name'] ?? null);
        Setting::set('support_email', $data['support_email'] ?? null);
        Setting::set('payment_gateway', $data['payment_gateway'] ?? 'manual');
        Setting::set('maintenance_mode', $data['maintenance_mode'] ?? '0');

        return redirect()->route('platform.settings.index')->with('success', 'Pengaturan tersimpan.');
    }
}
