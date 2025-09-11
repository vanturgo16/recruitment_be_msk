<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;
use App\Models\MstRules;
use App\Models\JobApply;

class DashboardController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $underDev = optional(MstRules::where('rule_name', 'Development')->first())->rule_value;
        return view('dashboard.index', compact('underDev'));
    }

    public function getDataSummary(Request $request)
    {
        $dateFrom = $request->dateFrom ? Carbon::parse($request->dateFrom)->startOfDay() : now()->startOfMonth();
        $dateTo   = $request->dateTo   ? Carbon::parse($request->dateTo)->endOfDay()   : now()->endOfMonth();

        if ($request->ajax()) {
            $datas = JobApply::whereBetween('created_at', [$dateFrom, $dateTo])->get();

            $countNotReview  = $datas->whereNull('is_seen')->count();
            $countInProgress = $datas->where('status', 0)->count();
            $countReject     = $datas->where('status', 2)->count();
            $countHired      = $datas->where('status', 1)->count();

            return response()->json([
                'countNotReview'  => $countNotReview,
                'countInProgress' => $countInProgress,
                'countReject'     => $countReject,
                'countHired'      => $countHired,
            ]);
        }
    }

    public function switchTheme(Request $request)
    {
        DB::beginTransaction();
        try {
            $statusBefore = User::where('id', auth()->user()->id)->first()->is_darkmode;
            $status = ($statusBefore == 1) ? null : 1;
            User::where('id', auth()->user()->id)->update(['is_darkmode' => $status]);

            //Audit Log
            $this->auditLogs('Switch Theme');
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_switch_theme'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_switch_theme')]);
        }
    }
}
