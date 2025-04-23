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

class JoblistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $positions = MstPosition::select('mst_positions.id', 'mst_positions.position_name', 'mst_departments.dept_name')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->orderBy('mst_positions.id_dept')
            ->get();

        if ($request->ajax()) {
            $datas = Joblist::select('joblists.*', 'employees.email')
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
        return view('joblist.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_emp' => 'required',
            'reason' => 'required',
        ]);
        // Check Existing Data
        if(Blacklist::where('id_emp', $request->id_emp)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }

        DB::beginTransaction();
        try {
            $store = Blacklist::create([
                'id_emp' => $request->id_emp,
                'reason' => $request->reason,
                'notes' => $request->notes
            ]);

            // Audit Log
            $this->auditLogs('Store New Employee Blacklist ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_add'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_add')]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_emp' => 'required',
            'reason' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(Blacklist::where('id_emp', $request->id_emp)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }
        // Check With Data Before
        $dataBefore = Blacklist::where('id', $id)->first();
        $dataBefore->id_emp = $request->id_emp;
        $dataBefore->reason = $request->reason;
        $dataBefore->notes = $request->notes;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                Blacklist::where('id', $id)->update([
                    'id_emp' => $request->id_emp,
                    'reason' => $request->reason,
                    'notes' => $request->notes
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Employee Blacklist ID: ' . $id);
                DB::commit();
                return redirect()->back()->with('success', __('messages.success_update'));
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => __('messages.fail_update')]);
            }
        } else {
            return redirect()->back()->with(['info' => __('messages.same_data')]);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            Blacklist::where('id', $id)->delete();

            // Audit Log
            $this->auditLogs('Delete Selected Employee Blacklist ID: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_delete')]);
        }
    }
}
