<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;
use App\Traits\ApiRegionalTrait;

// Model
use App\Models\Office;
use App\Models\MstDropdowns;

class OfficeController extends Controller
{
    use AuditLogsTrait;
    use ApiRegionalTrait;

    public function index(Request $request)
    {
        $officeTypes = MstDropdowns::where('category', 'Type Office')->get();
        $listProvinces = $this->getProvinceRegional();

        if ($request->ajax()) {
            $datas = Office::orderBy('created_at')->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('office.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Office');
        return view('office.index', compact('officeTypes', 'listProvinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'postal_code' => 'required'
        ]);

        DB::beginTransaction();
        try{
            Office::create([
                'type' => $request->type,
                'code' => $request->code,
                'name' => $request->name,
                'address' => $request->address,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'subdistrict' => $request->subdistrict,
                'postal_code' => $request->postal_code,
                'is_active' => 1
            ]);

            //Audit Log
            $this->auditLogs('Create New Office');
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_add'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_add')]);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = Office::where('id', $id)->first();
        $officeTypes = MstDropdowns::where('category', 'Type Office')->get();

        $listProvinces = $this->getProvinceRegional();
        $provinceId = collect($listProvinces)->firstWhere('nama', $data->province)['id'] ?? null;

        $listCities = json_decode($this->getCity($provinceId), true);
        $cityId = collect($listCities)->firstWhere('nama', $data->city)['id'] ?? null;

        $listDistricts = json_decode($this->getDistrict($cityId), true);
        $districtId = collect($listDistricts)->firstWhere('nama', $data->district)['id'] ?? null;

        $listSubDistricts = json_decode($this->getSubDistrict($districtId), true);

        //Audit Log
        $this->auditLogs('View Edit Form Office ID (' . $id . ')');
        return view('office.edit', compact('data', 'officeTypes', 'listProvinces', 'listCities', 'listDistricts', 'listSubDistricts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'postal_code' => 'required'
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = Office::where('id', $id)->first();
        $dataBefore->type = $request->type;
        $dataBefore->code = $request->code;
        $dataBefore->name = $request->name;
        $dataBefore->address = $request->address;
        $dataBefore->province = $request->province;
        $dataBefore->city = $request->city;
        $dataBefore->district = $request->district;
        $dataBefore->subdistrict = $request->subdistrict;
        $dataBefore->postal_code = $request->postal_code;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                Office::where('id', $id)->update([
                    'type' => $request->type,
                    'code' => $request->code,
                    'name' => $request->name,
                    'address' => $request->address,
                    'province' => $request->province,
                    'city' => $request->city,
                    'district' => $request->district,
                    'subdistrict' => $request->subdistrict,
                    'postal_code' => $request->postal_code
                ]);

                // Audit Log
                $this->auditLogs('Update Office ID: ' . $id);
                DB::commit();
                return redirect()->route('office.index')->with('success', __('messages.success_update'));
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->route('office.index')->with(['fail' => __('messages.fail_update')]);
            }
        } else {
            return redirect()->back()->with(['info' => __('messages.same_data')]);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);
        $data = Office::where('id', $id)->first();
        DB::beginTransaction();
        try {
            Office::where('id', $id)->update([ 'is_active' => 1 ]);

            // Audit Log
            $this->auditLogs('Activate Office ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_activate') . ' ' . $data->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_activate') . ' ' . $data->name . '!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);
        $data = Office::where('id', $id)->first();
        DB::beginTransaction();
        try {
            Office::where('id', $id)->update([ 'is_active' => 0 ]);

            // Audit Log
            $this->auditLogs('Deactivate Office ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_deactivate') . ' ' . $data->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_deactivate') . ' ' . $data->name . '!']);
        }
    }
}
