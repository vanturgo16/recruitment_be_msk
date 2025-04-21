<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Employee;

class EmployeeController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Employee::orderBy('created_at')->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('employee.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Employee');
        return view('employee.index');
    }

    public function detail($id)
    {
        $id = decrypt($id);
        $data = Employee::where('id', $id)->first();

        //Audit Log
        $this->auditLogs('View Detail Employee ID (' . $id . ')');
        return view('employee.detail', compact('data'));
    }
}
