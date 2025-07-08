<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $table = 'interview_schedules';

    protected $fillable = [
        'id_jobapply',
        'interview_date',
        'interview_address',
        'interview_notes',
        'result_attachment',
        'result_notes',
        'approved_by_1',
        'interview_status',
        'ready_tested',
        'created_by',
    ];

    // Relationships
    public function jobapply()
    {
        return $this->belongsTo(JobApply::class, 'id_jobapply');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approval1()
    {
        return $this->belongsTo(User::class, 'approved_by_1');
    }
}
