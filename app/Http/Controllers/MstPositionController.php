<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstDepartment;
use App\Models\MstPosition;

class MstPositionController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $listDepartments = MstDepartment::orderBy('created_at')->get();
        if ($request->ajax()) {
            $datas = MstPosition::select('mst_positions.*', 'mst_departments.dept_name')
                ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
                ->orderBy('mst_positions.id_dept')
                ->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($listDepartments) {
                    return view('position.action', compact('data', 'listDepartments'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Position');
        return view('position.index', compact('listDepartments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dept' => 'required',
            'position_name' => 'required',
            'hie_level' => 'required',
        ]);

        DB::beginTransaction();
        try{
            MstPosition::create([
                'id_dept' => $request->id_dept,
                'position_name' => $request->position_name,
                'hie_level' => $request->hie_level,
                'notes' => $request->notes
            ]);

            //Audit Log
            $this->auditLogs('Create New Position');
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
            'id_dept' => 'required',
            'position_name' => 'required',
            'hie_level' => 'required',
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstPosition::where('id', $id)->first();
        $dataBefore->id_dept = $request->id_dept;
        $dataBefore->position_name = $request->position_name;
        $dataBefore->hie_level = $request->hie_level;
        $dataBefore->notes = $request->notes;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstPosition::where('id', $id)->update([
                    'id_dept' => $request->id_dept,
                    'position_name' => $request->position_name,
                    'hie_level' => $request->hie_level,
                    'notes' => $request->notes
                ]);

                if ($dataBefore->hie_level != $request->hie_level) {
                    $empIds = Employee::where('id_position', $id)->pluck('id');
                    if ($empIds->isNotEmpty()) {
                        User::whereIn('id_emp', $empIds)->update([
                            'hie_level' => $request->hie_level
                        ]);
                    }
                }

                // Audit Log
                $this->auditLogs('Update Position ID: ' . $id);
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
