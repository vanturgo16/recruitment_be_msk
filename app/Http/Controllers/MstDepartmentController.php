<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstDivision;
use App\Models\MstDepartment;

class MstDepartmentController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $listDivisions = MstDivision::orderBy('created_at')->get();
        if ($request->ajax()) {
            $datas = MstDepartment::select('mst_departments.*', 'mst_divisions.div_name')
                ->leftjoin('mst_divisions', 'mst_departments.id_div', 'mst_divisions.id')
                ->orderBy('mst_departments.id_div')
                ->orderBy('mst_departments.created_at')
                ->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($listDivisions) {
                    return view('department.action', compact('data', 'listDivisions'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Department');
        return view('department.index', compact('listDivisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_div' => 'required',
            'dept_name' => 'required',
        ]);

        DB::beginTransaction();
        try{
            MstDepartment::create([
                'id_div' => $request->id_div,
                'dept_name' => $request->dept_name,
                'notes' => $request->notes
            ]);

            //Audit Log
            $this->auditLogs('Create New Department');
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
            'id_div' => 'required',
            'dept_name' => 'required',
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstDepartment::where('id', $id)->first();
        $dataBefore->id_div = $request->id_div;
        $dataBefore->dept_name = $request->dept_name;
        $dataBefore->notes = $request->notes;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstDepartment::where('id', $id)->update([
                    'id_div' => $request->id_div,
                    'dept_name' => $request->dept_name,
                    'notes' => $request->notes
                ]);

                // Audit Log
                $this->auditLogs('Update Department ID: ' . $id);
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
}
