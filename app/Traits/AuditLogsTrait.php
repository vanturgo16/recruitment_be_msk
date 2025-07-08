<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Models\MstRules;
use Browser;

trait AuditLogsTrait
{
    public function auditLogs($activity)
    {
        if (MstRules::where('rule_name', 'Turn On Audit Log')->first()->rule_value == 1) {
            AuditLog::create([
                'username' => auth()->user()->email,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'location' => '0',
                'access_from' => Browser::browserName(),
                'activity' => $activity,
            ]);
        }
    }
}
