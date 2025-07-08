<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if (!$request->has('monthYear') || $request->monthYear == "") {
            $monthYear = Carbon::now()->format('Y-m');
        } else {
            $monthYear = $request->monthYear;
        }
        [$year, $month] = explode('-', $monthYear);
        $datas = AuditLog::select('audit_logs.*', 'created_at as created')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($datas)->toJson();
        }

        // Audit Log
        $this->auditLogs('View List Audit Log');
        return view('auditlog.index', compact('monthYear'));
    }
}
