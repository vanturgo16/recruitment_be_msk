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

    public function joblist()
    {
        return $this->belongsTo(Joblist::class, 'id_joblist');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'id_candidate');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'id_candidate', 'id_candidate');
    }
}
