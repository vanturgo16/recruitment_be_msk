<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstDivision;

class MstDivisionController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = MstDivision::orderBy('created_at')->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('division.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Division');
        return view('division.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'div_name' => 'required'
        ]);

        DB::beginTransaction();
        try{
            MstDivision::create([
                'div_name' => $request->div_name,
                'notes' => $request->notes
            ]);

            //Audit Log
            $this->auditLogs('Create New Division');
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
            'div_name' => 'required',
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstDivision::where('id', $id)->first();
        $dataBefore->div_name = $request->div_name;
        $dataBefore->notes = $request->notes;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstDivision::where('id', $id)->update([
                    'div_name' => $request->div_name,
                    'notes' => $request->notes
                ]);

                // Audit Log
                $this->auditLogs('Update Division ID: ' . $id);
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
