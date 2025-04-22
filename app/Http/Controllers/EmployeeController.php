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
            $datas = Employee::select('employees.*', 'mst_positions.position_name', 'offices.name as office_name')
                ->leftjoin('mst_positions', 'employees.id_position', 'mst_positions.id')
                ->leftjoin('offices', 'employees.placement_id', 'offices.id')
                ->orderBy('employees.created_at')
                ->get();
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
        $data = Employee::select('employees.*', 'mst_positions.position_name', 'mst_departments.dept_name', 'mst_divisions.div_name', 'offices.name as office_name')
            ->leftjoin('mst_positions', 'employees.id_position', 'mst_positions.id')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->leftjoin('mst_divisions', 'mst_departments.id_div', 'mst_divisions.id')
            ->leftjoin('offices', 'employees.placement_id', 'offices.id')
            ->where('employees.id', $id)
            ->first();

            // dd($data);

        //Audit Log
        $this->auditLogs('View Detail Employee ID (' . $id . ')');
        return view('employee.detail', compact('data'));
    }
}
