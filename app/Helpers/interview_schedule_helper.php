<?php
// Helper for JobApplied Detail: check if interview schedule exists for joblist
if (!function_exists('has_interview_schedule')) {
    function has_interview_schedule($id_jobapply) {
        return \App\Models\InterviewSchedule::where('id_jobapply', $id_jobapply)->exists();
    }
}
// Helper for JobApplied Detail: get interview schedule id for jobapply
if (!function_exists('get_interview_schedule_id')) {
    function get_interview_schedule_id($id_jobapply) {
        $schedule = \App\Models\InterviewSchedule::where('id_jobapply', $id_jobapply)->first();
        return $schedule ? $schedule->id : null;
    }
}
