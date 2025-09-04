<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\Mail\NotificationInternal;
use App\Mail\NotificationSchedule;
use App\Models\JobApply;
use App\Models\MstRules;
use App\Models\SigningSchedule;
use App\Traits\PhaseLoggable;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SigningScheduleController extends Controller
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

        $schedules = SigningSchedule::with(['jobapply.candidate', 'jobapply.joblist.position.department', 'creator']);

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

        $schedules = $schedules->orderBy('sign_date', 'desc')->get();
        
        return view('signing_schedule.index', compact('schedules'));
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
        return view('signing_schedule.create', compact('id_jobapply', 'applicant_name', 'position_name', 'jobapply'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jobapply' => 'required|exists:job_applies,id',
            'sign_date' => 'required|date|after_or_equal:today',
            'sign_address' => 'required|string',
            'sign_notes' => 'nullable|string',
        ]);
        
        SigningSchedule::create([
            'id_jobapply' => $request->id_jobapply,
            'sign_date' => $request->sign_date,
            'sign_address' => $request->sign_address,
            'sign_notes' => $request->sign_notes,
            'created_by' => Auth::id(),
        ]);

        //start MAIL
        $jobApply = JobApply::findOrFail($request->id_jobapply);

        $mailData = [
            'candidate_name' => $jobApply->candidate->candidate_first_name,
            'candidate_email' => $jobApply->candidate->email,
            'position_applied' => $jobApply->joblist->position->position_name,
            'created_at' => $jobApply->created_at,
            'location' => $request->sign_address,
            'date'  => $request->sign_date,
            'phase' => 'SIGNING AS EMPLOYEE',
            'message' => $request->sign_notes,
        ];

        //phaseLog
        $this->logPhase($request->id_jobapply, 'SIGNING AS EMPLOYEE', $request->sign_notes, 'Set schedule signing as employee by admin recruiter', '1');

        // Initiate Variable
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toemail = ($development == 1) 
            ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
            : $jobApply->candidate->email;

        // [ MAILING ]
        Mail::to($toemail)->send(new NotificationSchedule($mailData));

        return redirect()->route('signing_schedule.index')->with('success', 'SIGNING schedule created successfully.');
    }

    public function edit($id)
    {
        $schedule = SigningSchedule::with(['jobapply.candidate', 'jobapply.joblist'])->findOrFail($id);
        return view('signing_schedule.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jobapply' => 'required|exists:job_applies,id',
            'sign_date' => 'required|date',
            'sign_address' => 'required|string',
            'sign_notes' => 'nullable|string',
        ]);
        $schedule = SigningSchedule::findOrFail($id);
        $schedule->update([
            'id_jobapply' => $request->id_jobapply,
            'sign_date' => $request->sign_date,
            'sign_address' => $request->sign_address,
            'sign_notes' => $request->sign_notes,
        ]);
        return redirect()->route('signing_schedule.index')->with('success', 'SIGNING schedule updated successfully.');
    }

    public function updateResult(Request $request, $id)
    {
        //dd($id);
        $request->validate([
            'result_notes' => 'nullable|string',
        ]);

        $userId = $user = Auth::user()->id;
        DB::beginTransaction();
        try {
            //update table SIGNING schedule
            // 1. Temukan record berdasarkan ID
            $schedule = SigningSchedule::find($id);

            // Pastikan record ditemukan sebelum melanjutkan
            if ($schedule) {
                //4. send mail to internal user (dimatiin karna tidak perlu approval head)
                // $mailData = [
                //     'current_phase'     => 'SIGNING AS EMPLOYEE',
                //     'job_user'          => $schedule->jobApply->joblist->userRequest->name,
                //     'candidate_name'    => $schedule->jobApply->candidate->candidate_first_name,
                //     'position_applied'  => $schedule->jobApply->joblist->position->position_name,
                //     'created_at'        => $schedule->jobApply->created_at,
                //     'status'            => 'NEED APPROVAL TO SIGNING'
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
                        'status'            => $status
                    ]);

                //Inactive User Candidate
                $email = $schedule->jobApply->candidate->email;

                //phaseLog
                $this->logPhase($id_jobapply, $progressStatus . ' AFTER SIGNING SESSION', $request->result_notes, 'Reject after review result signing by admin recruiter', '1');

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
                $progressStatus = 'HIRED';
                $statusReadyHired = '1';

                //Perbarui atribut-atribut model
                //$schedule->result_attachment = $attPath->getPath() . '/' . $attPath->getFilename();
                $schedule->result_notes = $request->result_notes;
                $schedule->approved_by_1 = $userId; // Ini adalah nilai yang Anda cari
                $schedule->sign_status = $request->approval_action;
                $schedule->ready_hired = $statusReadyHired;

                //Simpan perubahan ke database
                $schedule->save();

                $updateJobApply = JobApply::where('id', $id_jobapply)
                    ->update([
                        'progress_status' => $progressStatus
                    ]);
            }

            DB::commit();
            return redirect()->route('signing_schedule.index')->with('success', 'SIGNING result saved successfully.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->route('signing_schedule.index')->with('fail', 'SIGNING result saved failed.');
        }
    }

    // Dimatikan karena flow berubah
    // public function submitToHired(Request $request, $id)
    // {
    //     $id = decrypt($id);
        
    //     DB::beginTransaction();
    //     try {
    //         $userId = $user = Auth::user()->id;
    //         $now = now();
            
    //         //update table SIGNING schedule
    //         // 1. Temukan record berdasarkan ID
    //         $schedule = SigningSchedule::find($id);

    //         if($request->approval_action == '1'){
    //             $progressStatus = 'HIRED';
    //             $statusReadyHired = '1';
    //             $status = '1';
    //         }

    //         if($request->approval_action == '2'){
    //             $progressStatus = 'REJECTED';
    //             $statusReadyHired = '2';
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
    //             $schedule->ready_hired = $statusReadyHired;
    //             $schedule->sign_status = $status;

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
    //             $this->logPhase($id_jobapply, $progressStatus . ' AFTER SIGNING SESSION', '', 'Reject after review result signing by department head/user', '1');
    //         }
    //         else{
    //             $updateJobApply = JobApply::where('id', $id_jobapply)
    //                 ->update([
    //                     'approved_to_hired_by_1' => $userId,
    //                     'approved_to_hired_at_1' => $now,
    //                     'progress_status'        => $progressStatus
    //                 ]);
    //         }
    //         DB::commit();
    //         return redirect()->route('signing_schedule.index')->with('success', 'This Candidate is saved as ' . $progressStatus);
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         DB::rollBack();
    //         return redirect()->route('signing_schedule.index')->with('fail', 'Failed update data.');
    //     }
    // }

    public function destroy($id)
    {
        $schedule = SigningSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('signing_schedule.index')->with('success', 'SIGNING schedule deleted successfully.');
    }
}
