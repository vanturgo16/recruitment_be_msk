<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstDropdowns;

class MstDropdownController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstDropdowns::orderBy('category')->orderBy('created_at')->get();
        $categories = MstDropdowns::get()->unique('category');

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($categories) {
                    return view('dropdown.action', compact('data', 'categories'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Dropdown');
        return view('dropdown.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
        ]);
        $category = $request->category === 'NewCat' ? $request->addcategory : $request->category;
        // Check Existing Data
        if (MstDropdowns::where('category', $category)->where('name_value', $request->name_value)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }

        DB::beginTransaction();
        try {
            $store = MstDropdowns::create([
                'category' => $category,
                'name_value' => $request->name_value,
                'code_format' => $request->code_format,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New Dropdown ID: ' . $store->id);
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
            'category' => 'required',
            'name_value' => 'required',
        ]);
        $category = $request->category === 'NewCat' ? $request->addcategory : $request->category;

        $id = decrypt($id);
        // Check Existing Data
        if(MstDropdowns::where('category', $category)->where('name_value', $request->name_value)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }
        // Check With Data Before
        $dataBefore = MstDropdowns::where('id', $id)->first();
        $dataBefore->category = $category;
        $dataBefore->name_value = $request->name_value;
        $dataBefore->code_format = $request->code_format;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstDropdowns::where('id', $id)->update([
                    'category' => $category,
                    'name_value' => $request->name_value,
                    'code_format' => $request->code_format
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Dropdown ID: ' . $id);
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

    public function enable($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstDropdowns::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstDropdowns::where('id', $id)->first()->name_value;

            // Audit Log
            $this->auditLogs('Enable Selected Dropdown ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_activate') . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_activate') . $nameValue . '!']);
        }
    }

    public function disable($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstDropdowns::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstDropdowns::where('id', $id)->first()->name_value;

            // Audit Log
            $this->auditLogs('Disable Selected Dropdown ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_deactivate') . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_deactivate') . $nameValue . '!']);
        }
    }
}
