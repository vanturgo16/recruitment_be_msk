<?php

namespace App\Traits;

use App\Models\PhaseLog;
use Illuminate\Support\Facades\Auth;

trait PhaseLoggable
{
    /**
     * Log phase change for a model.
     *
     * @param string $phase
     * @param string|null $notes
     * @param int|null $userId
     * @return void
     */
    public function logPhase($phase, $notes, $activity, $candidate_timeline = null)
    {
        PhaseLog::create([
            'phase'              => $phase,
            'activity'           => $activity,
            'notes'              => $notes,
            'user_id'            => Auth::user()->id,
            'candidate_timeline' => $candidate_timeline,
        ]);
    }
}
