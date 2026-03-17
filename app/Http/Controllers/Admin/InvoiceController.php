<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Foundation;
use App\Models\Plan;

class InvoiceController extends Controller
{
    public function index()
    {
        $search = request()->query('search');

        $query = Foundation::query()->with('plan');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        $foundations = $query->orderBy('subscription_ends_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Foundation::count(),
            'expiring' => Foundation::whereNotNull('subscription_ends_at')->where('subscription_ends_at', '<=', now()->addDays(7))->count(),
        ];

        return view('admin.invoices.index', compact('foundations', 'search', 'stats'));
    }
}
