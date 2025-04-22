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

class BlacklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $employees = Employee::select('id', 'email')->whereNotIn('id', function($query) {
            $query->select('id_emp')->from('blacklists');
        })->get();

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
        return view('blacklist.index', compact('employees'));
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
