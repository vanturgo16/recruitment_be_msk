<?php
use App\Models\SigningSchedule;

if (!function_exists('has_signing_schedule')) {
    function has_signing_schedule($id_jobapply) {
        return SigningSchedule::where('id_jobapply', $id_jobapply)->exists();
    }
}

if (!function_exists('get_signing_schedule_id')) {
    function get_signing_schedule_id($id_jobapply) {
        $schedule = SigningSchedule::where('id_jobapply', $id_jobapply)->first();
        return $schedule ? $schedule->id : null;
    }
}
