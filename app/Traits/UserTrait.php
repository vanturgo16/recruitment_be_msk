<?php

namespace App\Traits;

use App\Models\User;

trait UserTrait
{
    public function inactiveUser($email){
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update(['is_active' => 0]);
            return true;
        }
        return false;
    }
}
