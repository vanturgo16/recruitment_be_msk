<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferingSchedule extends Model
{
    use HasFactory;
    protected $table = 'offering_schedules';

    protected $fillable = [
        'id_jobapply',
        'offering_date',
        'offering_address',
        'offering_notes',
        'result_attachment',
        'result_notes',
        'approved_by_1',
        'offering_status',
        'ready_mcu',
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
