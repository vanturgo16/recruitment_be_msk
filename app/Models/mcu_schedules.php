<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mcu_schedules extends Model
{
    use HasFactory;
    protected $table = 'mcu_schedules';

    protected $fillable = [
        'id_jobapply',
        'mcu_date',
        'mcu_address',
        'mcu_notes',
        'result_attachment',
        'result_notes',
        'approved_by_1',
        'mcu_status',
        'ready_hired',
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
