<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Blacklist;
use App\Models\Employee;
use App\Models\MainProfile;
use App\Models\MstDropdowns;
use App\Models\User;
use App\Models\Candidate;

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

        $template = (object) [
            "file_name" => "Import_Employee_Data_MSK.xlsx",
            "path"      => "assets/file/template/Import_Employee_Data_MSK.xlsx",
        ];

        //Audit Log
        $this->auditLogs('View List Employee');
        return view('employee.index', compact('listReasons', 'template'));
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

    public function importData(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $import = new \App\Imports\EmployeeImport;
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        if (count($import->errors) > 0) {
            return back()->withErrors($import->errors);
        }

        DB::beginTransaction();
        try {
            foreach ($import->validRows as $item) {
                $emp = Employee::create([
                    'emp_no' => $item['emp_no'],
                    'email' => $item['email'],
                    'id_position' => $item['id_position'],
                    'placement_id' => $item['placement_id'],
                    'reportline_1' => $item['reportline_1'],
                    'reportline_2' => $item['reportline_2'],
                    'reportline_3' => $item['reportline_3'],
                    'reportline_4' => $item['reportline_4'],
                    'reportline_5' => $item['reportline_5'],
                    'is_active' => 1,
                    'join_date' => $item['join_date'],
                ]);

                $candidate = Candidate::create([
                    'id_user' => 0,
                    'id_emp' => $emp->id,
                    'candidate_first_name' => $item['first_name'],
                    'candidate_last_name' => $item['last_name'],
                    'phone' => $item['phone'],
                    'email' => $item['email'],
                    'id_card_no' => $item['id_card_no'],
                    'tnc_check' => 1,
                ]);

                MainProfile::create([
                    'id_candidate' => $candidate->id,
                    'id_emp' => $emp->id,
                    'id_card_address' => $item['id_card_address'],
                    'domicile_address' => $item['domicile_address'],
                    'birthplace' => $item['birthplace'],
                    'birthdate' => $item['birthdate'],
                    'gender' => $item['gender'],
                    'marriage_status' => $item['marriage_status'],
                ]);

                //Update Employee
                Employee::where('id', $emp->id)->update([
                    'id_candidate' => $candidate->id
                ]);
            }

            // Audit Log
            $this->auditLogs('Import Employee Data');

            DB::commit();
            return back()->with('success', 'Employee data imported successfully!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Import data employee failed!']);
        }
    }
}
