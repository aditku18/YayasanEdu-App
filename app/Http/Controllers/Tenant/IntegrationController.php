<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function api()
    {
        // Logic for API Key management
        return view('tenant.integrations.api');
    }

    public function whatsapp()
    {
        // Logic for WhatsApp Gateway integration
        return view('tenant.integrations.whatsapp');
    }

    public function absensi()
    {
        // Logic for Attendance Device integration
        return view('tenant.integrations.absensi');
    }

    public function google()
    {
        // Logic for Google Workspace integration
        return view('tenant.integrations.google');
    }

    public function payment()
    {
        // Logic for Payment Gateway integration
        return view('tenant.integrations.payment');
    }
}
