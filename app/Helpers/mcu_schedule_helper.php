<?php
// Helper for JobApplied Detail: check if interview schedule exists for joblist

use App\Models\mcu_schedules;

if (!function_exists('has_mcu_schedule')) {
    function has_mcu_schedule($id_jobapply) {
        return mcu_schedules::where('id_jobapply', $id_jobapply)->exists();
    }
}
// Helper for JobApplied Detail: get offering schedule id for jobapply
if (!function_exists('get_mcu_schedule_id')) {
    function get_mcu_schedule_id($id_jobapply) {
        $schedule = mcu_schedules::where('id_jobapply', $id_jobapply)->first();
        return $schedule ? $schedule->id : null;
    }
}
