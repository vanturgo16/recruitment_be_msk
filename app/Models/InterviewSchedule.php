<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $table = 'interview_schedules';

    protected $guarded = ['id'];

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

    public function approvalUser()
    {
        return $this->belongsTo(User::class, 'approved_to_offering_by_1');
    }
}
