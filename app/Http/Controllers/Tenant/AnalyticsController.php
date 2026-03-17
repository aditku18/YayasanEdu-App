<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function usage()
    {
        // Logic for usage analytics
        return view('tenant.analytics.usage');
    }
}
