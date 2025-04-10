<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;

class ProfileController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        //Audit Log
        $this->auditLogs('View Page Profile');
        return view('profile.index');
    }
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photoProfile' => 'mimes:jpg,jpeg,png|max:5240',
        ]);
        $urlProfileImg = null;
        if ($request->hasFile('photoProfile')) {
            $path = $request->file('photoProfile');
            $urlProfileImg = $path->move('storage/profileImg', $path->hashName());
        }

        DB::beginTransaction();
        try {
            // Delete File Before
            $pathBefore = User::where('id', auth()->user()->id)->first()->profile_path;
            if ($pathBefore != null) {
                $file_path = public_path($pathBefore);
                if (File::exists($file_path)) {
                    File::delete($file_path);
                }
            }
            // Update
            User::where('id', auth()->user()->id)->update(['photo_path' => $urlProfileImg]);

            //Audit Log
            $this->auditLogs('Update Photo Profile');
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_update'));
        } catch (Exception $e) {
            DB::rollBack();
            if ($urlProfileImg && file_exists(public_path($urlProfileImg))) { unlink(public_path($urlProfileImg)); }
            return redirect()->back()->with(['fail' => __('messages.fail_update')]);
        }
    }
}
