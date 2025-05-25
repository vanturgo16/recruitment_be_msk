<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use App\Models\JobApply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewScheduleController extends Controller
{
    public function index()
    {
        $schedules = InterviewSchedule::with(['jobapply.candidate', 'jobapply.joblist', 'creator'])->orderBy('interview_date', 'desc')->get();
        return view('interview_schedule.index', compact('schedules'));
    }

    public function create(Request $request)
    {
        $id_jobapply = $request->get('id_jobapply');
        $jobapply = null;
        $applicant_name = $request->get('applicant_name');
        $position_name = $request->get('position_name');
        if ($id_jobapply) {
            $jobapply = JobApply::with(['candidate', 'joblist'])->find($id_jobapply);
            if ($jobapply) {
                $applicant_name = $jobapply->candidate ? $jobapply->candidate->candidate_first_name . ' ' . $jobapply->candidate->candidate_last_name : '-';
            }
        }
        return view('interview_schedule.create', compact('id_jobapply', 'applicant_name', 'position_name', 'jobapply'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jobapply' => 'required|exists:job_applies,id',
            'interview_date' => 'required|date',
            'interview_address' => 'required|string',
            'interview_notes' => 'nullable|string',
        ]);
        InterviewSchedule::create([
            'id_jobapply' => $request->id_jobapply,
            'interview_date' => $request->interview_date,
            'interview_address' => $request->interview_address,
            'interview_notes' => $request->interview_notes,
            'created_by' => Auth::id(),
        ]);
        return redirect()->route('interview_schedule.index')->with('success', 'Interview schedule created successfully.');
    }

    public function edit($id)
    {
        $schedule = InterviewSchedule::with(['jobapply.candidate', 'jobapply.joblist'])->findOrFail($id);
        return view('interview_schedule.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jobapply' => 'required|exists:job_applies,id',
            'interview_date' => 'required|date',
            'interview_address' => 'required|string',
            'interview_notes' => 'nullable|string',
        ]);
        $schedule = InterviewSchedule::findOrFail($id);
        $schedule->update([
            'id_jobapply' => $request->id_jobapply,
            'interview_date' => $request->interview_date,
            'interview_address' => $request->interview_address,
            'interview_notes' => $request->interview_notes,
        ]);
        return redirect()->route('interview_schedule.index')->with('success', 'Interview schedule updated successfully.');
    }

    public function destroy($id)
    {
        $schedule = InterviewSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('interview_schedule.index')->with('success', 'Interview schedule deleted successfully.');
    }
}
