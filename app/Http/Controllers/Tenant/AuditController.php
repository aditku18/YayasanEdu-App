<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        // Logic for audit logs
        $audits = \App\Models\Audit::latest()->paginate(20);
        return view('tenant.audits.index', compact('audits'));
    }

    public function show($id)
    {
        try {
            $audit = \App\Models\Audit::findOrFail($id);
            return view('tenant.audits.show', compact('audit'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('tenant.audit.index')
                ->with('error', 'Audit log not found');
        }
    }

    public function export(Request $request)
    {
        $audits = \App\Models\Audit::latest()->get();
        
        $filename = 'audit-logs-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($audits) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Action', 'Module', 
                'Table Name', 'Record ID', 'IP Address', 'Status', 
                'Description', 'Created At'
            ]);
            
            // CSV Data
            foreach ($audits as $audit) {
                fputcsv($file, [
                    $audit->id,
                    $audit->user_name ?? 'System',
                    $audit->user_email ?? '-',
                    $audit->action ?? '-',
                    $audit->module ?? '-',
                    $audit->table_name ?? '-',
                    $audit->record_id ?? '-',
                    $audit->ip_address ?? '-',
                    $audit->status ?? '-',
                    $audit->description ?? '-',
                    $audit->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
