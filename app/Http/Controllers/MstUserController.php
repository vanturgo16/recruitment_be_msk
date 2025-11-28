<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

// Mail
use App\Mail\SendEmailPassword;
// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;
use App\Models\MstDropdowns;
use App\Models\MstRules;
use App\Models\MstEmployees;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\MstPosition;

class MstUserController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {
        $roleUsers = MstDropdowns::where('category', 'Role User')->where('is_active', 1)->get();

        //Audit Log
        $this->auditLogs('View List Manage User');
        return view('users.index', compact('roleUsers'));
    }

    public function datas(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::where('role', '!=', 'Candidate')->orderByDesc('last_seen')->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('users.action', compact('data'));
                })->toJson();
        }
    }

    private function generateRandomPassword($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    public function store(Request $request)
    {
        // Validate Request
        $request->validate([
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Employee::where('email', $value)->exists()) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'role' => 'required',
        ]);
        //Prevent Create Role Super Admin, If Not Super Admin
        if (auth()->user()->role != 'Super Admin' && $request->role == 'Super Admin') {
            return redirect()->back()->withInput()->with(['fail' => __('messages.user_fail1')]);
        }
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->with('warning', __('messages.user_fail2'));
        }

        // Initiate Variable
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toemail = ($development == 1) 
                ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                : $request->email;
            
        DB::beginTransaction();
        try {
            $password = $this->generateRandomPassword(8);
            //search id_emp by email
            $emp = Employee::where('email', $request->email)->first();
            if (!$emp) {
                return redirect()->back()->with(['fail' => 'Employee not found for this email']);
            }
            $hie_level = MstPosition::where('id', $emp->id_position)->value('hie_level');
            if (!$hie_level) {
                return redirect()->back()->with(['fail' => 'Hierarchy level not found for employee position']);
            }
            $candidate = Candidate::where('id_emp', $emp->id)->first();
            if (!$candidate) {
                return redirect()->back()->with(['fail' => 'Candidate Data not found for this email']);
            }

            $user = User::create([
                'id_candidate' => $candidate->id,
                'id_emp' => $emp->id,
                'hie_level' => $hie_level,
                'name' => $candidate->candidate_first_name . " " . $candidate->candidate_last_name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'is_active' => 1,
                'role' => $request->role
            ]);

            // Update Candidate
            Candidate::where('id_emp', $emp->id)->update([
                'id_user' => $user->id
            ]);

            // [ MAILING ]
            $mailContent = new SendEmailPassword('New', $request->name, $request->email, $password);
            Mail::to($toemail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Create New User (' . $request->email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_add')]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_add')]);
        }
    }

    public function reset($id)
    {
        $id = decrypt($id);
        // Initiate Variable
        $data = User::where('id', $id)->first();
        $password = $this->generateRandomPassword(8);
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toemail = ($development == 1) 
                ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                : $data->email;

        DB::beginTransaction();
        try {
            User::where('id', $id)->update([
                'password' => Hash::make($password),
                'last_modified_password_at' => now(),
            ]);

            // [ MAILING ]
            $mailContent = new SendEmailPassword('Reset', $data->name, $data->email, $password);
            Mail::to($toemail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Reset Password User (' . $data->email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.user_success1') . $data->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.user_fail3') . $data->email . '!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = User::where('id', $id)->first();
        $roleUsers = MstDropdowns::where('category', 'Role User')->where('is_active', 1)->orWhere('name_value', $data->role)->get();

        //Audit Log
        $this->auditLogs('View Edit User ID (' . $id . ')');
        return view('users.edit', compact('data', 'roleUsers'));
    }

    public function update(Request $request, $id)
    {
        // Validate Request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);
        //Prevent Create Role Super Admin, If Not Super Admin
        if (auth()->user()->role != 'Super Admin' && $request->role == 'Super Admin') {
            return redirect()->back()->withInput()->with(['fail' => __('messages.user_fail1')]);
        }

        $iduser = decrypt($id);
        if(User::where('email', $request->email)->where('id', '!=', $iduser)->exists()){
            return redirect()->back()->withInput()->with(['fail' => 'Email has already registered']);
        }

        $dataBefore = User::where('id', $iduser)->first();
        $dataBefore->name = $request->name;
        $dataBefore->email = $request->email;
        $dataBefore->role = $request->role;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                User::where('id', $iduser)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role
                ]);

                if($dataBefore->email != $request->email){
                    Employee::where('id', $dataBefore->id_emp)->update([
                        'email' => $request->email
                    ]);
                }

                //Audit Log
                $this->auditLogs('Update User (' . $dataBefore->email . ')');
                DB::commit();
                return redirect()->route('user.index')->with(['success' => __('messages.success_update')]);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => __('messages.fail_update')]);
            }
        } else {
            return redirect()->route('user.index')->with(['info' => __('messages.same_data')]);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            $email = User::where('id', $id)->first()->email;
            User::where('id', $id)->delete();

            // Audit Log
            $this->auditLogs('Delete User (' . $email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_delete') . ' ' . $email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_delete') . ' ' . $email . '!']);
        }
    }

    public function toggleStatus($id, $field, $value)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->update([$field => $value]);

            // Log Action
            if ($field === 'is_active') {
                $action = $value ? 'Activate' : 'Deactivate';
            } elseif ($field === 'is_two_fa') {
                $action = $value ? 'Enable 2FA' : 'Disable 2FA';
            } else {
                $action = 'Update';
            }

            $this->auditLogs("{$action} User ({$user->email})");

            DB::commit();
            return back()->with('success', "Success {$action} User {$user->email}");
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('fail', "Failed to {$action} User {$user->email}!");
        }
    }

    public function enable2fa($id)
    {
        return $this->toggleStatus($id, 'is_two_fa', 1);
    }
    public function disable2fa($id)
    {
        return $this->toggleStatus($id, 'is_two_fa', null);
    }

    public function activate($id)
    {
        return $this->toggleStatus($id, 'is_active', 1);
    }
    public function deactivate($id)
    {
        return $this->toggleStatus($id, 'is_active', 0);
    }

    public function check_email(Request $request)
    {
        $email = $request->input('email');
        $isEmailRegist = MstEmployees::where('email', $email)->first();
        if ($isEmailRegist || $email == null) {
            return response()->json(['status' => 'registered']);
        } else {
            return response()->json(['status' => 'notregistered']);
        }
    }

    public function candidates(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::where('role', 'Candidate')->orderByDesc('last_seen')->get();
            return DataTables::of($datas)->toJson();
        }

        $isEnable2FA = optional(MstRules::where('rule_name', 'Enable 2FA Candidates')->first())->rule_value;

        return view('users.candidates', compact('isEnable2FA'));
    }

    
    public function enable2faCandidate()
    {
        MstRules::updateOrCreate(
            ['rule_name' => 'Enable 2FA Candidates'],
            ['rule_value' => 1]
        );

        // Audit Log
        $this->auditLogs('Enable 2FA for Candidates');

        return redirect()->back()->with('success', '2FA has been enabled for Candidates.');
    }

    public function disable2faCandidate()
    {
        MstRules::updateOrCreate(
            ['rule_name' => 'Enable 2FA Candidates'],
            ['rule_value' => 0]
        );

        // Audit Log
        $this->auditLogs('Disable 2FA for Candidates');

        return redirect()->back()->with('success', '2FA has been disabled for Candidates.');
    }
}
