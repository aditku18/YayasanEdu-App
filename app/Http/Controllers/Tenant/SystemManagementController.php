<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\User;

class SystemManagementController extends Controller
{
    public function users()
    {
        $users = User::paginate(20);
        return view('yayasan.users-global', compact('users'));
    }

    public function billing()
    {
        return view('yayasan.billing');
    }

    public function monitoring()
    {
        return view('yayasan.monitoring');
    }
}
