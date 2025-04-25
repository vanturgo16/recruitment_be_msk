<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Joblist;
use App\Models\MstPosition;
use App\Models\Employee;
use App\Models\MstDropdowns;

class JoblistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $positions = MstPosition::select('mst_positions.id', 'mst_positions.position_name', 'mst_departments.dept_name')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->orderBy('mst_positions.id_dept')
            ->get();
        $educations = MstDropdowns::where('category', 'Education')->get();

        if ($request->ajax()) {
            $datas = Joblist::select('joblists.*', 'mst_positions.position_name', 'mst_departments.dept_name', 'employees.email')
                ->leftjoin('mst_positions', 'joblists.id_position', 'mst_positions.id')
                ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
                ->leftjoin('employees', 'joblists.position_req_user', 'employees.id')
                ->orderBy('joblists.created_at')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('joblist.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Joblist');
        return view('joblist.index', compact('positions', 'educations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_position' => 'required',
            'rec_date_start' => 'required',
            'rec_date_end' => 'nullable|date|after_or_equal:rec_date_start',
            'jobdesc' => 'required',
            'requirement' => 'required',
            'position_req_user' => 'required',
        ]);
        // Check Existing Data
        if(Joblist::where('id_position', $request->id_position)->where('is_active', 1)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }

        DB::beginTransaction();
        try {
            $store = Joblist::create([
                'id_position' => $request->id_position,
                'rec_date_start' => $request->rec_date_start,
                'rec_date_end' => $request->rec_date_end,
                'jobdesc' => $request->jobdesc,
                'requirement' => $request->requirement,
                'min_education' => $request->min_education,
                'min_yoe' => $request->min_yoe,
                'min_age' => $request->min_age,
                'max_candidate' => $request->max_candidate,
                'position_req_user' => $request->position_req_user,
                'number_of_applicant' => 0,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New Joblist ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_add'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_add')]);
        }
    }
    
    public function detail($id)
    {
        $id = decrypt($id);
        $positions = MstPosition::select('mst_positions.id', 'mst_positions.position_name', 'mst_departments.dept_name')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->orderBy('mst_positions.id_dept')
            ->get();
        $educations = MstDropdowns::where('category', 'Education')->get();

        $data = Joblist::select('joblists.*', 'mst_positions.position_name', 'mst_departments.dept_name', 'employees.email')
            ->leftjoin('mst_positions', 'joblists.id_position', 'mst_positions.id')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->leftjoin('employees', 'joblists.position_req_user', 'employees.id')
            ->where('joblists.id', $id)
            ->first();

        $deptId = optional(MstPosition::find($data->id_position))->id_dept;
        if (!$deptId) {
            $listEmployee = collect();
        } else {
            $listEmployee = Employee::select('id', 'email')->whereIn('id_position', function ($query) use ($deptId) {
                $query->select('id')
                    ->from('mst_positions')
                    ->where('id_dept', $deptId);
            })->get();
        }

        //Audit Log
        $this->auditLogs('View Detail Joblist ID (' . $id . ')');
        return view('joblist.detail', compact('positions', 'educations', 'data', 'listEmployee'));
    }

    public function applicantList($id)
    {
        $id = decrypt($id);

        //Audit Log
        $this->auditLogs('View List Applicant Joblist ID (' . $id . ')');
        return view('joblist.applicant_list');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_position' => 'required',
            'rec_date_start' => 'required',
            'rec_date_end' => 'nullable|date|after_or_equal:rec_date_start',
            'jobdesc' => 'required',
            'requirement' => 'required',
            'position_req_user' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(Joblist::where('id_position', $request->id_position)->where('is_active', 1)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }
        // Check With Data Before
        $dataBefore = Joblist::where('id', $id)->first();
        $dataBefore->id_position = $request->id_position;
        $dataBefore->rec_date_start = $request->rec_date_start;
        $dataBefore->rec_date_end = $request->rec_date_end;
        $dataBefore->jobdesc = $request->jobdesc;
        $dataBefore->requirement = $request->requirement;
        $dataBefore->min_education = $request->min_education;
        $dataBefore->min_yoe = $request->min_yoe;
        $dataBefore->min_age = $request->min_age;
        $dataBefore->max_candidate = $request->max_candidate;
        $dataBefore->position_req_user = $request->position_req_user;

        if($request->max_candidate < $dataBefore->number_of_applicant){
            return redirect()->back()->with(['fail' => 'Max candidate cannot be less than the current number of applicants']);
        }

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                Joblist::where('id', $id)->update([
                    'id_position' => $request->id_position,
                    'rec_date_start' => $request->rec_date_start,
                    'rec_date_end' => $request->rec_date_end,
                    'jobdesc' => $request->jobdesc,
                    'requirement' => $request->requirement,
                    'min_education' => $request->min_education,
                    'min_yoe' => $request->min_yoe,
                    'min_age' => $request->min_age,
                    'max_candidate' => $request->max_candidate,
                    'position_req_user' => $request->position_req_user,
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Joblist ID: ' . $id);
                DB::commit();
                return redirect()->back()->with(['success', __('messages.success_update')]);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => __('messages.fail_update')]);
            }
        } else {
            return redirect()->back()->with(['info' => __('messages.same_data')]);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            Joblist::where('id', $id)->update([ 'is_active' => 1 ]);

            // Audit Log
            $this->auditLogs('Activate Joblist ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_activate') . ' Joblist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_activate') . ' Joblist']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            Joblist::where('id', $id)->update([ 'is_active' => 0 ]);

            // Audit Log
            $this->auditLogs('Deactivate Joblist ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_deactivate') . ' Joblist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_deactivate') . ' Joblist' . '!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        $data = Joblist::where('id', $id)->first();
        if ($data->number_of_applicant > 0) {
            return redirect()->back()->with(['fail' => 'Cannot delete this job listing because there are applicants associated with it.']);
        }

        DB::beginTransaction();
        try {
            Joblist::where('id', $id)->delete();

            // Audit Log
            $this->auditLogs('Delete Selected Joblist ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_delete')]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_delete')]);
        }
    }

    public function getUsersByPosition($id)
    {
        $deptId = optional(MstPosition::find($id))->id_dept;
        if (!$deptId) {
            return response()->json([]);
        }
        $employee = Employee::whereIn('id_position', function ($query) use ($deptId) {
            $query->select('id')
                ->from('mst_positions')
                ->where('id_dept', $deptId);
        })->pluck('email', 'id');
        return response()->json($employee);
    }
}
