<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseLog extends Model
{
    use HasFactory;
    protected $table = 'phase_logs';

    protected $guarded = [
        'id'
    ];

    // Relationships
    public function jobapply()
    {
        return $this->belongsTo(JobApply::class, 'id_jobapply');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
