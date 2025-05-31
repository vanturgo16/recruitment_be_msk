<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSchedule extends Model
{
    use HasFactory;

    protected $table = 'test_schedules';

    protected $fillable = [
        'id_jobapply',
        'test_date',
        'test_address',
        'test_notes',
        'result_attachment',
        'result_notes',
        'approved_by_1',
        'test_status',
        'ready_offering',
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
