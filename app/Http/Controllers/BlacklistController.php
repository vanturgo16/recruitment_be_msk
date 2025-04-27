<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Blacklist;
use App\Models\Employee;
use App\Models\MstDropdowns;

class BlacklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Blacklist::select('blacklists.*', 'employees.email')
                ->leftjoin('employees', 'blacklists.id_emp', 'employees.id')
                ->orderBy('blacklists.created_at')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('blacklist.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Employee Blacklist');
        return view('blacklist.index');
    }
}
