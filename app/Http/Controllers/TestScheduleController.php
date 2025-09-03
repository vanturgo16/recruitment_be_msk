<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\Mail\NotificationInternal;
use App\Mail\NotificationSchedule;
use App\Models\TestSchedule;
use App\Models\JobApply;
use App\Models\MstRules;
use App\Traits\PhaseLoggable;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TestScheduleController extends Controller
{
    use UserTrait;
    use PhaseLoggable;
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            // Memuat relasi 'employee' dan 'department' dari 'employee'
            $user->load('employee.position');

            // Sekarang Anda bisa mengakses:
            $departmentName = $user->employee->position->department->dept_name; // Contoh, jika dept_name ada di model Department
        }

        $schedules = TestSchedule::with(['jobapply.candidate', 'jobapply.joblist.position.department', 'creator']);

        // Filter by id_jobapply jika ada di request
        if ($request->has('id_jobapply') && $request->id_jobapply) {
            $id_jobapply = null;
            try {
                $id_jobapply = decrypt($request->id_jobapply);
            } catch (\Exception $e) {}
            if ($id_jobapply) {
                $schedules->where('id_jobapply', $id_jobapply);
            }
        }

        if (isset($departmentName) && $departmentName && $user->role == 'Employee') {
            // joblist memiliki relasi ke department (yaitu joblist.department_id)
            $schedules->whereHas('jobapply.joblist.position.department', function ($query) use ($departmentName) {
                $query->where('dept_name', $departmentName);
            });
        }

        $schedules = $schedules->orderBy('test_date', 'desc')->get();
        
        return view('test_schedule.index', compact('schedules'));
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
        return view('test_schedule.create', compact('id_jobapply', 'applicant_name', 'position_name', 'jobapply'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jobapply' => 'required|exists:job_applies,id',
            'test_date' => 'required|date|after_or_equal:today',
            'test_address' => 'required|string',
            'test_notes' => 'nullable|string',
        ]);
        
        TestSchedule::create([
            'id_jobapply' => $request->id_jobapply,
            'test_date' => $request->test_date,
            'test_address' => $request->test_address,
            'test_notes' => $request->test_notes,
            'created_by' => Auth::id(),
        ]);

        //start MAIL
        $jobApply = JobApply::findOrFail($request->id_jobapply);

        $mailData = [
            'candidate_name' => $jobApply->candidate->candidate_first_name,
            'candidate_email' => $jobApply->candidate->email,
            'position_applied' => $jobApply->joblist->position->position_name,
            'created_at' => $jobApply->created_at,
            'location' => $request->test_address,
            'date'  => $request->test_date,
            'phase' => 'TESTING ASSESSMENT',
            'message' => $request->test_notes,
        ];

        //phaseLog
        $this->logPhase($request->id_jobapply, 'TESTING', $request->test_notes, 'Set schedule test by admin recruiter', '1');

        // Initiate Variable
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toemail = ($development == 1) 
            ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
            : $jobApply->candidate->email;

        // [ MAILING ]
        Mail::to($toemail)->send(new NotificationSchedule($mailData));

        return redirect()->route('test_schedule.index')->with('success', 'Test schedule created successfully.');
    }

    public function edit($id)
    {
        $schedule = TestSchedule::with(['jobapply.candidate', 'jobapply.joblist'])->findOrFail($id);
        return view('test_schedule.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jobapply' => 'required|exists:job_applies,id',
            'test_date' => 'required|date',
            'test_address' => 'required|string',
            'test_notes' => 'nullable|string',
        ]);
        $schedule = TestSchedule::findOrFail($id);
        $schedule->update([
            'id_jobapply' => $request->id_jobapply,
            'test_date' => $request->test_date,
            'test_address' => $request->test_address,
            'test_notes' => $request->test_notes,
        ]);
        return redirect()->route('test_schedule.index')->with('success', 'Test schedule updated successfully.');
    }

    public function updateResult(Request $request, $id)
    {
        //dd($id);
        $request->validate([
            //'result_attachment' => 'required|mimes:pdf|max:500',
            'result_notes' => 'nullable|string',
        ]);

        // if ($request->hasFile('result_attachment')) {
        //     $path = $request->file('result_attachment');
        //     $attPath = $path->move('storage/resultTest', $path->hashName());
        // }

        $userId = $user = Auth::user()->id;
        DB::beginTransaction();
        try {
            //update table Test schedule
            // 1. Temukan record berdasarkan ID
            $schedule = TestSchedule::find($id);

            // Pastikan record ditemukan sebelum melanjutkan
            if ($schedule) {
                // //4. send mail to internal user (dimatiin karna tidak perlu approval head)
                // $mailData = [
                //     'current_phase'     => 'TESTING',
                //     'job_user'          => $schedule->jobApply->joblist->userRequest->name,
                //     'candidate_name'    => $schedule->jobApply->candidate->candidate_first_name,
                //     'position_applied'  => $schedule->jobApply->joblist->position->position_name,
                //     'created_at'        => $schedule->jobApply->created_at,
                //     'status'            => 'NEED APPROVAL TO OFFERING'
                // ];
                
                // // Initiate Variable
                // $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
                // $toemail = ($development == 1) 
                // ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                // : $schedule->jobApply->joblist->userRequest->email;
                
                // // [ MAILING ]
                // Mail::to($toemail)->send(new NotificationInternal($mailData));

                $id_jobapply = $schedule->id_jobapply;              
            }

            //update table Job Apply
            if($request->approval_action == '2'){
                $progressStatus = 'REJECTED';
                $status = '2';

                $updateJobApply = JobApply::where('id', $id_jobapply)
                    ->update([
                        'status' => $status
                    ]);
                
                //Inactive User Candidate
                $email = $schedule->jobApply->candidate->email;

                //phaseLog
                $this->logPhase($id_jobapply, $progressStatus . ' AFTER TESTING SESSION', $request->result_notes, 'Reject after review result test by admin recruiter', '1');

                $mailData = [
                    'candidate_name' => $schedule->jobApply->candidate->candidate_first_name,
                    'candidate_email' => $schedule->jobApply->candidate->email,
                    'position_applied' => $schedule->jobApply->joblist->position->position_name,
                    'created_at' => $schedule->jobApply->created_at,
                    'status' => $progressStatus,
                    'message' => "We appreciate you taking the time to apply for this position. While your qualifications are impressive, we have decided to pursue other applicants whose profiles were a closer match for our current needs.",
                ];

                // Initiate Variable
                $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
                $toemail = ($development == 1) 
                        ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                        : $schedule->jobApply->candidate->email;

                // [ MAILING ]
                Mail::to($toemail)->send(new Notification($mailData));
            }
            else{
                $progressStatus = 'INTERVIEW';
                $statusReadyInterview = '1';

                //Perbarui atribut-atribut model
                //$schedule->result_attachment = $attPath->getPath() . '/' . $attPath->getFilename();
                $schedule->result_notes = $request->result_notes;
                $schedule->approved_by_1 = $userId; // Ini adalah nilai yang Anda cari
                $schedule->test_status = $request->approval_action;
                $schedule->ready_interview = $statusReadyInterview;

                //Simpan perubahan ke database
                $schedule->save();

                $updateJobApply = JobApply::where('id', $id_jobapply)
                    ->update([
                        'progress_status' => $progressStatus
                    ]);
            }

            DB::commit();
            return redirect()->route('test_schedule.index')->with('success', 'Test result saved successfully.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->route('test_schedule.index')->with('fail', 'Test result saved failed.');
        }
    }

    // gak dipake karena flow nya berubah
    // public function submitToOffer(Request $request, $id){
    //     $id = decrypt($id);
        
    //     DB::beginTransaction();
    //     try {
    //         $userId = $user = Auth::user()->id;
    //         $now = now();
            
    //         //update table Test schedule
    //         // 1. Temukan record berdasarkan ID
    //         $schedule = TestSchedule::find($id);

    //         if($request->approval_action == '1'){
    //             $progressStatus = 'OFFERING';
    //             $statusReadyOffering = '1';
    //             $status = '1';
    //         }

    //         if($request->approval_action == '2'){
    //             $progressStatus = 'REJECTED';
    //             $statusReadyOffering = '2';
    //             $status = '2';

    //             //Inactive User Candidate
    //             $email = $schedule->jobApply->candidate->email;

    //             $mailData = [
    //                 'candidate_name' => $schedule->jobApply->candidate->candidate_first_name,
    //                 'candidate_email' => $schedule->jobApply->candidate->email,
    //                 'position_applied' => $schedule->jobApply->joblist->position->position_name,
    //                 'created_at' => $schedule->jobApply->created_at,
    //                 'status' => $progressStatus,
    //                 'message' => "We appreciate you taking the time to apply for this position. While your qualifications are impressive, we have decided to pursue other applicants whose profiles were a closer match for our current needs.",
    //             ];

    //             // Initiate Variable
    //             $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
    //             $toemail = ($development == 1) 
    //                     ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
    //                     : $schedule->jobApply->candidate->email;

    //             // [ MAILING ]
    //             Mail::to($toemail)->send(new Notification($mailData));
    //         }

    //         // Pastikan record ditemukan sebelum melanjutkan
    //         if ($schedule) {
    //             // 2. Perbarui atribut-atribut model
    //             $schedule->ready_offering = $statusReadyOffering;
    //             $schedule->test_status = $status;

    //             // 3. Simpan perubahan ke database
    //             $schedule->save();
    //             $id_jobapply = $schedule->id_jobapply;              
    //         }

    //         //update table Job Apply
    //         if($progressStatus == 'REJECTED'){
    //             $updateJobApply = JobApply::where('id', $id_jobapply)
    //                 ->update([
    //                     'status'                    => $status
    //                 ]);

    //             //phaseLog
    //             $this->logPhase($id_jobapply, $progressStatus . ' AFTER TEST SESSION', '', 'Reject after review result test by department head/user', '1');
    //         }
    //         else{
    //             $updateJobApply = JobApply::where('id', $id_jobapply)
    //                 ->update([
    //                     'approved_to_offering_by_1' => $userId,
    //                     'approved_to_offering_at_1' => $now,
    //                     'progress_status'           => $progressStatus
    //                 ]);
    //         }
    //         DB::commit();
    //         return redirect()->route('test_schedule.index')->with('success', 'This Candidate is saved as ' . $progressStatus);
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         DB::rollBack();
    //         return redirect()->route('test_schedule.index')->with('fail', 'Failed update data.');
    //     }
    // }

    public function destroy($id)
    {
        $schedule = TestSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('test_schedule.index')->with('success', 'Test schedule deleted successfully.');
    }
}
