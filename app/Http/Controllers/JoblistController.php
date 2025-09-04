<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\Mail\NotificationInternal;
use App\Models\Candidate;
use App\Models\EducationInfo;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Joblist;
use App\Models\MstPosition;
use App\Models\Employee;
use App\Models\GeneralInfo;
use App\Models\JobApply;
use App\Models\MainProfile;
use App\Models\MstDropdowns;
use App\Models\MstRules;
use App\Models\Office;
use App\Models\PhaseLog;
use App\Models\User;
use App\Models\WorkExpInfo;
use App\Traits\PhaseLoggable;
use App\Traits\ProfilTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\TestSchedule;
use App\Models\InterviewSchedule;
use App\Models\OfferingSchedule;
use App\Models\mcu_schedules;
use App\Models\SigningSchedule;

class JoblistController extends Controller
{
    use AuditLogsTrait;
    use ProfilTrait;
    use UserTrait;
    use PhaseLoggable;
    public function index(Request $request)
    {
        $positions = MstPosition::select('mst_positions.id', 'mst_positions.position_name', 'mst_departments.dept_name')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->orderBy('mst_positions.id_dept')
            ->get();
        $educations = MstDropdowns::where('category', 'Education')->get();

        if ($request->ajax()) {
            $datas = Joblist::select('joblists.*', 'mst_positions.position_name', 'mst_departments.dept_name', 'employees.email')
                ->leftjoin('mst_positions', 'joblists.id_position', 'mst_positions.id')
                ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
                ->leftjoin('employees', 'joblists.position_req_user', 'employees.id')
                ->orderBy('joblists.created_at')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('joblist.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Joblist');
        return view('joblist.index', compact('positions', 'educations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_position' => 'required',
            'rec_date_start' => 'required',
            'rec_date_end' => 'nullable|date|after_or_equal:rec_date_start',
            'jobdesc' => 'required',
            'requirement' => 'required',
            'position_req_user' => 'required',
        ]);
        // Check Existing Data
        if(Joblist::where('id_position', $request->id_position)->where('is_active', 1)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }

        DB::beginTransaction();
        try {
            $store = Joblist::create([
                'id_position' => $request->id_position,
                'rec_date_start' => $request->rec_date_start,
                'rec_date_end' => $request->rec_date_end,
                'jobdesc' => $request->jobdesc,
                'requirement' => $request->requirement,
                'min_education' => $request->min_education,
                'min_yoe' => $request->min_yoe,
                'min_age' => $request->min_age,
                'max_candidate' => $request->max_candidate,
                'position_req_user' => $request->position_req_user,
                'number_of_applicant' => 0,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New Joblist ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_add'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_add')]);
        }
    }
    
    public function detail($id)
    {
        $id = decrypt($id);
        $positions = MstPosition::select('mst_positions.id', 'mst_positions.position_name', 'mst_departments.dept_name')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->orderBy('mst_positions.id_dept')
            ->get();
        $educations = MstDropdowns::where('category', 'Education')->get();

        $data = Joblist::select('joblists.*', 'mst_positions.position_name', 'mst_departments.dept_name', 'employees.email')
            ->leftjoin('mst_positions', 'joblists.id_position', 'mst_positions.id')
            ->leftjoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
            ->leftjoin('employees', 'joblists.position_req_user', 'employees.id')
            ->where('joblists.id', $id)
            ->first();

        $deptId = optional(MstPosition::find($data->id_position))->id_dept;
        if (!$deptId) {
            $listEmployee = collect();
        } else {
            $listEmployee = Employee::select('id', 'email')->whereIn('id_position', function ($query) use ($deptId) {
                $query->select('id')
                    ->from('mst_positions')
                    ->where('id_dept', $deptId);
            })->get();
        }

        //Audit Log
        $this->auditLogs('View Detail Joblist ID (' . $id . ')');
        return view('joblist.detail', compact('positions', 'educations', 'data', 'listEmployee'));
    }

    public function applicantList($id)
    {
        $id = decrypt($id);
        $offices = Office::orderBy('name')->get();

        //Audit Log
        $this->auditLogs('View List Applicant Joblist ID (' . $id . ')');
        return view('joblist.applicant_list');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_position' => 'required',
            'rec_date_start' => 'required',
            'rec_date_end' => 'nullable|date|after_or_equal:rec_date_start',
            'jobdesc' => 'required',
            'requirement' => 'required',
            'position_req_user' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(Joblist::where('id_position', $request->id_position)->where('is_active', 1)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }
        // Check With Data Before
        $dataBefore = Joblist::where('id', $id)->first();
        $dataBefore->id_position = $request->id_position;
        $dataBefore->rec_date_start = $request->rec_date_start;
        $dataBefore->rec_date_end = $request->rec_date_end;
        $dataBefore->jobdesc = $request->jobdesc;
        $dataBefore->requirement = $request->requirement;
        $dataBefore->min_education = $request->min_education;
        $dataBefore->min_yoe = $request->min_yoe;
        $dataBefore->min_age = $request->min_age;
        $dataBefore->max_candidate = $request->max_candidate;
        $dataBefore->position_req_user = $request->position_req_user;

        if($request->max_candidate < $dataBefore->number_of_applicant){
            return redirect()->back()->with(['fail' => 'Max candidate cannot be less than the current number of applicants']);
        }

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                Joblist::where('id', $id)->update([
                    'id_position' => $request->id_position,
                    'rec_date_start' => $request->rec_date_start,
                    'rec_date_end' => $request->rec_date_end,
                    'jobdesc' => $request->jobdesc,
                    'requirement' => $request->requirement,
                    'min_education' => $request->min_education,
                    'min_yoe' => $request->min_yoe,
                    'min_age' => $request->min_age,
                    'max_candidate' => $request->max_candidate,
                    'position_req_user' => $request->position_req_user,
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Joblist ID: ' . $id);
                DB::commit();
                return redirect()->back()->with(['success', __('messages.success_update')]);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => __('messages.fail_update')]);
            }
        } else {
            return redirect()->back()->with(['info' => __('messages.same_data')]);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            Joblist::where('id', $id)->update([ 'is_active' => 1 ]);

            // Audit Log
            $this->auditLogs('Activate Joblist ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_activate') . ' Joblist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_activate') . ' Joblist']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            Joblist::where('id', $id)->update([ 'is_active' => 0 ]);

            // Audit Log
            $this->auditLogs('Deactivate Joblist ID (' . $id . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_deactivate') . ' Joblist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_deactivate') . ' Joblist' . '!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        $data = Joblist::where('id', $id)->first();
        if ($data->number_of_applicant > 0) {
            return redirect()->back()->with(['fail' => 'Cannot delete this job listing because there are applicants associated with it.']);
        }

        DB::beginTransaction();
        try {
            Joblist::where('id', $id)->delete();

            // Audit Log
            $this->auditLogs('Delete Selected Joblist ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_delete')]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_delete')]);
        }
    }

    public function getUsersByPosition($id)
    {
        $deptId = optional(MstPosition::find($id))->id_dept;
        if (!$deptId) {
            return response()->json([]);
        }
        $employee = Employee::whereIn('id_position', function ($query) use ($deptId) {
            $query->select('id')
                ->from('mst_positions')
                ->where('id_dept', $deptId);
        })->pluck('email', 'id');
        return response()->json($employee);
    }

    public function jobApplied(Request $request)
    {
        $id_emp_login = Auth::user()->id_emp;
        $deptName = Employee::select('mst_departments.dept_name')
            ->leftJoin('mst_positions', 'employees.id_position', '=', 'mst_positions.id')
            ->leftJoin('mst_departments', 'mst_positions.id_dept', '=', 'mst_departments.id')
            ->where('employees.id', $id_emp_login)
            ->value('dept_name');

        // Jika yang login role-nya Employee, tambahkan filter dept_name
        if (Auth::user()->role === 'Employee' && $deptName) {
            $request->merge(['filter_dept_name' => $deptName]);
        }
        
        if ($request->ajax()) {
            $data = JobApply::select(
                'job_applies.id_joblist',
                'joblists.id_position',
                'joblists.created_at',
                'mst_positions.position_name',
                'mst_departments.dept_name',
                DB::raw('COUNT(job_applies.id) as count_noa'),
                DB::raw('SUM(CASE WHEN job_applies.is_approved_1 IS NULL THEN 1 ELSE 0 END) as count_unreviewed'),
                DB::raw('SUM(CASE WHEN job_applies.is_seen = 1 THEN 1 ELSE 0 END) as count_seen'),
                DB::raw('SUM(CASE WHEN job_applies.status = 2 THEN 1 ELSE 0 END) as count_rejected'),
                DB::raw('SUM(CASE WHEN job_applies.progress_status = "INTERVIEW" THEN 1 ELSE 0 END) as count_interviewed'),
                DB::raw('SUM(CASE WHEN job_applies.progress_status = "TESTED" THEN 1 ELSE 0 END) as count_tested'),
                DB::raw('SUM(CASE WHEN job_applies.progress_status = "OFFERING" THEN 1 ELSE 0 END) as count_offered'),
                DB::raw('SUM(CASE WHEN job_applies.progress_status = "MCU" THEN 1 ELSE 0 END) as count_mcu'),
                DB::raw('SUM(CASE WHEN job_applies.progress_status = "SIGN" THEN 1 ELSE 0 END) as count_signed'),
                DB::raw('SUM(CASE WHEN job_applies.progress_status = "HIRED" THEN 1 ELSE 0 END) as count_hired'),
                )
                ->leftJoin('joblists', 'job_applies.id_joblist', '=', 'joblists.id')
                ->leftJoin('mst_positions', 'joblists.id_position', '=', 'mst_positions.id')
                ->leftJoin('mst_departments', 'mst_positions.id_dept', '=', 'mst_departments.id')
                // filter by department if needed
                ->when($request->filter_dept_name ?? null, function ($query, $deptName) {
                $query->where('mst_departments.dept_name', $deptName);
                })
                ->groupBy('job_applies.id_joblist')
                ->orderBy('joblists.created_at', 'desc')
                ->get();

            return DataTables::of(collect($data))
                ->addColumn('position', function($row) {
                    return $row->position_name . ' (<b>' . $row->dept_name . '</b>)' . ' <br> <b>Publish At:</b> ' . $row->created_at->format('d M Y');
                })
                ->addColumn('number_of_applicant', function($row) {
                    return $row->count_noa . ' (<span style="color:red">' . $row->count_rejected . '</span>)';
                })
                ->addColumn('reviewed', function($row) {
                    return $row->count_seen;
                })
                ->addColumn('interviewed', function($row) {
                    return $row->count_interviewed;
                })
                ->addColumn('tested', function($row) {
                    return $row->count_tested;
                })
                ->addColumn('offered', function($row) {
                    return $row->count_offered;
                })
                ->addColumn('mcu', function($row) {
                    return $row->count_mcu;
                })
                ->addColumn('signed', function($row) {
                    return $row->count_signed;
                })
                ->addColumn('hired', function($row) {
                    return $row->count_hired;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="'.route('jobapplied.detail', encrypt($row->id_joblist)).'" class="btn btn-info btn-sm">Show All Applicant</a>';
                })
                ->rawColumns(['action', 'position', 'number_of_applicant'])
                ->toJson();
        }
        return view('job_applied.index');
    }

    public function jobAppliedDetail($id)
    {
        $id = decrypt($id);
        $offices = Office::orderBy('name')->get();
        $datas = JobApply::select(
            'job_applies.*',
            'joblists.id_position',
            'mst_positions.position_name',
            'mst_positions.hie_level',
            'mst_departments.dept_name',
            'candidate.candidate_first_name',
            'candidate.candidate_last_name',
            'candidate.email',
            )
            ->leftJoin('joblists', 'job_applies.id_joblist', '=', 'joblists.id')
            ->leftJoin('mst_positions', 'joblists.id_position', '=', 'mst_positions.id')
            ->leftJoin('mst_departments', 'mst_positions.id_dept', '=', 'mst_departments.id')
            ->leftJoin('candidate', 'job_applies.id_candidate', '=', 'candidate.id')
            ->where('job_applies.id_joblist', $id)
            ->get();

        //cari untuk reportlines
        $id_dept = $datas[0]->joblist->position->id_dept;
        $positions = MstPosition::where('id_dept', $id_dept)
            ->where('hie_level', '<', '4')
            ->pluck('id');

        $reportlines = Employee::whereIn('id_position', $positions)
            ->leftJoin('mst_positions', 'employees.id_position', '=', 'mst_positions.id')
            ->leftJoin('users', 'employees.id', '=', 'users.id_emp')
            ->get();

        return view('job_applied.detail', compact('datas', 'offices', 'positions', 'reportlines'));
    }

    public function jobAppliedSeen($id)
    {
        $idJobApply = decrypt($id);
        $jobApply = JobApply::where('id', $idJobApply)->first();

        if($jobApply->is_seen <> 1){ //jika baru dilihat kirim email
            $mailData = [
                'candidate_name' => $jobApply->candidate->candidate_first_name,
                'candidate_email' => $jobApply->candidate->email,
                'position_applied' => $jobApply->joblist->position->position_name,
                'created_at' => $jobApply->created_at,
                'status' => "REVIEWED",
                'message' => "Your application is being reviewed",
            ];
    
            // Initiate Variable
            $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
            $toemail = ($development == 1) 
                    ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                    : $jobApply->candidate->email;
    
            // [ MAILING ]
            Mail::to($toemail)->send(new Notification($mailData));
            
            //phaseLog
            $this->logPhase($idJobApply, 'REVIEWED ADMINISTRATION', '', 'Review job by admin recruiter', '1');
        }

        if ($jobApply) {
            $jobApply->is_seen = 1;
            $jobApply->save();
        }

        // Redirect ke halaman info (GET)
        return redirect()->route('jobapplied.applicantinfo', encrypt($idJobApply));
    }

    public function jobAppliedApproveAdmin(Request $request, $id)
    {
        $idJobApply = decrypt($id);
        $jobApply = JobApply::findOrFail($idJobApply);
        $action = $request->input('approval_action');
        if ($action === 'approve') {
            $decision = 'APPROVED';
            $jobApply->is_approved_1 = 1;
            $jobApply->progress_status = 'TESTED'; //langsung interview tanpa approval head
        } else {
            $decision = 'REJECTED';
            $jobApply->is_approved_1 = 0;
            $jobApply->status = 2;

            //Inactive User Candidate
            $email = $jobApply->getUser->email;

            $mailData = [
                'candidate_name' => $jobApply->candidate->candidate_first_name,
                'candidate_email' => $jobApply->candidate->email,
                'position_applied' => $jobApply->joblist->position->position_name,
                'created_at' => $jobApply->created_at,
                'status' => $decision,
                'message' => "We appreciate you taking the time to apply for this position. While your qualifications are impressive, we have decided to pursue other applicants whose profiles were a closer match for our current needs.",
            ];

            // Initiate Variable
            $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
            $toemail = ($development == 1) 
                    ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                    : $jobApply->candidate->email;

            // [ MAILING ]
            Mail::to($toemail)->send(new Notification($mailData));

            //phaseLog
            $this->logPhase($idJobApply, $decision . ' REVIEW ADMINISTRATION', $request->input('approved_reason_1'), 'Reject approval after review administration by admin recruiter', '1');
        }
        $jobApply->approved_by_1 = Auth::user()->id;
        $jobApply->approved_at_1 = now();
        $jobApply->approved_reason_1 = $request->input('approved_reason_1');
        $jobApply->save();

        // //send mail to internal user (dimatikan karna tidak butuh approval head)
        // $mailData = [
        //     'current_phase'     => 'REVIEWED ADMINISTRATION',
        //     'job_user'          => $jobApply->joblist->userRequest->name,
        //     'candidate_name'    => $jobApply->candidate->candidate_first_name,
        //     'position_applied'  => $jobApply->joblist->position->position_name,
        //     'created_at'        => $jobApply->created_at,
        //     'status'            => 'NEED APPROVAL TO INTERVIEW',
        // ];
        
        // // Initiate Variable
        // $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        // $toemail = ($development == 1) 
        // ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
        // : $jobApply->joblist->userRequest->email;
        
        // // [ MAILING ]
        // Mail::to($toemail)->send(new NotificationInternal($mailData));

        return redirect()->back()->with('success', 'Applicant administration approval processed successfully.');
    }

    //dimatikan karna tidak butuh approval head
    // public function jobAppliedApproveHead(Request $request, $id)
    // {
    //     $idJobApply = decrypt($id);
    //     $jobApply = JobApply::findOrFail($idJobApply);
    //     $action = $request->input('approval_action_2');
    //     if ($action === 'approve') {
    //         $decision = 'APPROVED';
    //         $jobApply->is_approved_2 = 1;
    //         $jobApply->progress_status = 'INTERVIEW';
    //     } else {
    //         $decision = 'REJECTED';
    //         $jobApply->is_approved_2 = 0;
    //         $jobApply->status = 2;

    //         $mailData = [
    //             'candidate_name' => $jobApply->candidate->candidate_first_name,
    //             'candidate_email' => $jobApply->candidate->email,
    //             'position_applied' => $jobApply->joblist->position->position_name,
    //             'created_at' => $jobApply->created_at,
    //             'status' => $jobApply->progress_status,
    //             'message' => "We appreciate you taking the time to apply for this position. While your qualifications are impressive, we have decided to pursue other applicants whose profiles were a closer match for our current needs.",
    //         ];

    //         // Initiate Variable
    //         $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
    //         $toemail = ($development == 1) 
    //                 ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
    //                 : $jobApply->candidate->email;

    //         // [ MAILING ]
    //         Mail::to($toemail)->send(new Notification($mailData));

    //         //phaseLog
    //         $this->logPhase($idJobApply, $decision . ' REVIEW ADMINISTRATION', $request->input('approved_reason_2'), 'Reject approval after review administration by department head/user', '1');
    //     }
    //     $jobApply->approved_by_2 = Auth::user()->id;
    //     $jobApply->approved_at_2 = now();
    //     $jobApply->approved_reason_2 = $request->input('approved_reason_2');
    //     $jobApply->save();

    //     return redirect()->back()->with('success', 'Applicant head approval processed successfully.');
    // }

    public function jobAppliedApplicantInfo(Request $request, $id)
    {
        $idJobApply = decrypt($id);

        if ($request->ajax()) {
            $datas = PhaseLog::select('phase_logs.*', 'users.name as created')
                ->leftjoin('users', 'phase_logs.user_id', 'users.id')
                ->where('phase_logs.id_jobapply', $idJobApply)
                ->orderBy('phase_logs.created_at', 'desc')
                ->get();
            return DataTables::of($datas)->toJson();
        }

        $jobApply = JobApply::findOrFail($idJobApply);
        $idJobList = $jobApply->id_joblist;
        $idCandidate = $jobApply->id_candidate;

        $candidate = Candidate::where('id', $idCandidate)->first();
        $mainProfile = MainProfile::where('id_candidate', $idCandidate)->first();
        $generalInfo = GeneralInfo::where('id_candidate', $idCandidate)->first();
        $eduInfo = EducationInfo::where('id_candidate', $idCandidate)->get();
        $workExpInfo = WorkExpInfo::where('id_candidate', $idCandidate)->get();

        $isEditable = !$this->checkApplicationIP($idCandidate);

        $gender = MstDropdowns::where('category', 'Gender')->pluck('name_value');
        $marriageStatus = MstDropdowns::where('category', 'Marriage Status')->pluck('name_value');
        $grade = MstDropdowns::where('category', 'Education')->pluck('name_value');
        $optionYN = MstDropdowns::where('category', 'OptionYN')->pluck('name_value');
        $sourceInfo = MstDropdowns::where('category', 'Source Info')->pluck('name_value');
        $expInfo = MstDropdowns::where('category', 'Exp Info')->pluck('name_value');

        $approved_by_1_name = null;
        if ($jobApply->approved_by_1) {
            $user = User::find($jobApply->approved_by_1);
            $approved_by_1_name = $user ? $user->name : $jobApply->approved_by_1;
        }

        $approved_by_2_name = null;
        if ($jobApply->approved_by_2) {
            $user2 = User::find($jobApply->approved_by_2);
            $approved_by_2_name = $user2 ? $user2->name : $jobApply->approved_by_2;
        }

        // Data Section / STEP
        $stepTest = TestSchedule::select('test_schedules.test_status as status', 'users.name', 'test_schedules.created_at', 'test_schedules.result_notes as note')
            ->leftjoin('users', 'test_schedules.approved_by_1', 'users.id')
            ->where('test_schedules.id_jobapply', $idJobApply)
            ->first();
        // $stepTest = InterviewSchedule::select('interview_schedules.test_status as status', 'users.name', 'interview_schedules.created_at', 'interview_schedules.result_notes as note')
        //     ->leftjoin('users', 'interview_schedules.approved_by_1', 'users.id')
        //     ->where('interview_schedules.id_jobapply', $idJobApply)
        //     ->first();
        // $stepTest = TestSchedule::select('test_schedules.test_status as status', 'users.name', 'test_schedules.created_at', 'test_schedules.result_notes as note')
        //     ->leftjoin('users', 'test_schedules.approved_by_1', 'users.id')
        //     ->where('test_schedules.id_jobapply', $idJobApply)
        //     ->first();
        // $stepTest = TestSchedule::select('test_schedules.test_status as status', 'users.name', 'test_schedules.created_at', 'test_schedules.result_notes as note')
        //     ->leftjoin('users', 'test_schedules.approved_by_1', 'users.id')
        //     ->where('test_schedules.id_jobapply', $idJobApply)
        //     ->first();
        // $stepTest = TestSchedule::select('test_schedules.test_status as status', 'users.name', 'test_schedules.created_at', 'test_schedules.result_notes as note')
        //     ->leftjoin('users', 'test_schedules.approved_by_1', 'users.id')
        //     ->where('test_schedules.id_jobapply', $idJobApply)
        //     ->first();

        // $stepInterview = InterviewSchedule::where('id_jobapply', $idJobApply)->first();
        // $stepOffering = OfferingSchedule::where('id_jobapply', $idJobApply)->first();
        // $stepMCU = mcu_schedules::where('id_jobapply', $idJobApply)->first();
        // $stepSign = SigningSchedule::where('id_jobapply', $idJobApply)->first();

        return view('job_applied.applicant_info', compact(
            'idJobList',
            'idJobApply',
            'candidate',
            'mainProfile',
            'generalInfo',
            'eduInfo',
            'workExpInfo',
            'isEditable',
            'gender',
            'marriageStatus',
            'grade',
            'optionYN',
            'sourceInfo',
            'expInfo',
            'jobApply',
            'approved_by_1_name',
            'approved_by_2_name',

            // 'stepTest',
            // 'stepInterview',
            // 'stepOffering',
            // 'stepMCU',
            // 'stepSign',
        ));
    }
}
