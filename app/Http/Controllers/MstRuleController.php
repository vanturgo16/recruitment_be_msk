<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstRules;

class MstRuleController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstRules::orderBy('created_at')->get();
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('rule.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Rule');
        return view('rule.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rule_name' => 'required',
            'rule_value' => 'required',
        ]);
        // Check Existing Data
        if(MstRules::where('rule_name', $request->rule_name)->where('rule_value', $request->rule_value)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }

        DB::beginTransaction();
        try {
            $store = MstRules::create([
                'rule_name' => $request->rule_name,
                'rule_value' => $request->rule_value
            ]);

            // Audit Log
            $this->auditLogs('Store New Rule ID: ' . $store->id);
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
            'rule_name' => 'required',
            'rule_value' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(MstRules::where('rule_name', $request->rule_name)->where('rule_value', $request->rule_value)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }
        // Check With Data Before
        $dataBefore = MstRules::where('id', $id)->first();
        $dataBefore->rule_name = $request->rule_name;
        $dataBefore->rule_value = $request->rule_value;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstRules::where('id', $id)->update([
                    'rule_name' => $request->rule_name,
                    'rule_value' => $request->rule_value
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Rule ID: ' . $id);
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
            MstRules::where('id', $id)->delete();

            // Audit Log
            $this->auditLogs('Delete Selected Rule ID: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_delete')]);
        }
    }
}
