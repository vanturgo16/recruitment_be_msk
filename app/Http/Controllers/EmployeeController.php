<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Employee;
use App\Models\MstDropdowns;
use App\Models\User;

class EmployeeController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $listReasons = MstDropdowns::where('category', 'Reason Blacklist')->get();
        if ($request->ajax()) {
            $datas = Employee::select('employees.*', 'mst_positions.position_name', 'offices.name as office_name')
                ->leftjoin('mst_positions', 'employees.id_position', 'mst_positions.id')
                ->leftjoin('offices', 'employees.placement_id', 'offices.id')
                ->orderBy('employees.created_at')
                ->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($listReasons) {
                    return view('employee.action', compact('data', 'listReasons'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Employee');
        return view('employee.index', compact('listReasons'));
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

        //Audit Log
        $this->auditLogs('View Detail Employee ID (' . $id . ')');
        return view('employee.detail', compact('data'));
    }

    public function activate($id)
    {
        $id = decrypt($id);
        $data = Employee::where('id', $id)->first();
        DB::beginTransaction();
        try {
            Employee::where('id', $id)->update([
                'inactive_date' => null,
                'is_active' => 1
            ]);
            User::where('email', $data->email)->update([ 'is_active' => 1 ]);
            Blacklist::where('id_emp', $id)->delete();

            // Audit Log
            $this->auditLogs('Activate Employee ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_activate') . ' ' . $data->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_activate') . ' ' . $data->email . '!']);
        }
    }

    public function deactivate(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required',
        ]);

        $id = decrypt($id);
        $data = Employee::where('id', $id)->first();
        DB::beginTransaction();
        try {
            Employee::where('id', $id)->update([
                'inactive_date' => $request->inactive_date,
                'is_active' => 0
            ]);
            User::where('email', $data->email)->update([ 'is_active' => 0 ]);
            Blacklist::updateOrCreate(
                ['id_emp' => $id],
                [
                    'reason' => $request->reason,
                    'notes' => $request->notes
                ]
            );

            // Audit Log
            $this->auditLogs('Deactivate Employee ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_deactivate') . ' ' . $data->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_deactivate') . ' ' . $data->email . '!']);
        }
    }
}
