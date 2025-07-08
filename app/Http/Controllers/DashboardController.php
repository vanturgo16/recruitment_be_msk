<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;

class DashboardController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        return view('dashboard.index');
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
