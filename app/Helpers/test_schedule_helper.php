<?php
// Helper for JobApplied Detail: check if interview schedule exists for joblist

use App\Models\TestSchedule;

if (!function_exists('has_test_schedule')) {
    function has_test_schedule($id_jobapply) {
        return TestSchedule::where('id_jobapply', $id_jobapply)->exists();
    }
}
// Helper for JobApplied Detail: get test schedule id for jobapply
if (!function_exists('get_test_schedule_id')) {
    function get_test_schedule_id($id_jobapply) {
        $schedule = TestSchedule::where('id_jobapply', $id_jobapply)->first();
        return $schedule ? $schedule->id : null;
    }
}
