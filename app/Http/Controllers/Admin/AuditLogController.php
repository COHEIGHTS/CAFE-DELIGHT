<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $action = $request->input('action');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = AuditLog::with('user');

        // Filter by action
        if ($action) {
            $query->where('action', $action);
        }

        // Search by user name or description
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $query->orderBy($sortBy, $sortOrder);

        $auditLogs = $query->paginate(20)->withQueryString();

        // Get unique actions for filter dropdown
        $actions = AuditLog::distinct()->pluck('action')->sort()->values();

        // Summary stats
        $totalLogs = AuditLog::count();
        $todayLogs = AuditLog::whereDate('created_at', today())->count();
        $uniqueUsers = AuditLog::distinct('user_id')->count();

        return view('admin.audit-logs', compact(
            'auditLogs',
            'search',
            'action',
            'actions',
            'sortBy',
            'sortOrder',
            'totalLogs',
            'todayLogs',
            'uniqueUsers',
        ));
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->input('search');
        $action = $request->input('action');

        $query = AuditLog::with('user');

        if ($action) {
            $query->where('action', $action);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $auditLogs = $query->latest()->limit(500)->get();

        return view('admin.audit-logs-pdf', compact('auditLogs'));
    }
}
