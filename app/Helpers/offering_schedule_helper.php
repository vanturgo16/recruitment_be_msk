<?php
// Helper for JobApplied Detail: check if interview schedule exists for joblist

use App\Models\OfferingSchedule;

if (!function_exists('has_offering_schedule')) {
    function has_offering_schedule($id_jobapply) {
        return OfferingSchedule::where('id_jobapply', $id_jobapply)->exists();
    }
}
// Helper for JobApplied Detail: get offering schedule id for jobapply
if (!function_exists('get_offering_schedule_id')) {
    function get_offering_schedule_id($id_jobapply) {
        $schedule = OfferingSchedule::where('id_jobapply', $id_jobapply)->first();
        return $schedule ? $schedule->id : null;
    }
}
