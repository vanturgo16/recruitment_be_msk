<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    use HasFactory;

    protected $table = 'job_applies';
    protected $guarded = [
        'id'
        // tambahkan kolom lain jika ada
    ];

    public function job()
    {
        return $this->belongsTo(Joblist::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
