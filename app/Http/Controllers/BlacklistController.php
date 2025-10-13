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
    
    public function detail($id)
    {
        $id = decrypt($id);
        $data = Blacklist::select('blacklists.*', 'employees.*', 'blacklists.created_at as bl_created_at', 'blacklists.updated_at as bl_updated_at',
                    'mst_positions.position_name', 'mst_departments.dept_name', 'mst_divisions.div_name', 'offices.name as office_name',
                    'candidate.*', 'main_profile.*')
                ->leftjoin('employees', 'blacklists.id_emp', 'employees.id')
                ->leftjoin('mst_positions', 'employees.id_position', 'mst_positions.id')
                ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
                ->leftjoin('mst_divisions', 'mst_departments.id_div', 'mst_divisions.id')
                ->leftjoin('offices', 'employees.placement_id', 'offices.id')
                
                ->leftjoin('candidate', 'blacklists.id_emp', 'candidate.id_emp')
                ->leftjoin('main_profile', 'blacklists.id_emp', 'main_profile.id_emp')
                ->where('blacklists.id', $id)
                ->first();

        //Audit Log
        $this->auditLogs('View Detail Employee ID (' . $id . ')');
        return view('blacklist.detail', compact('data'));
    }
}
