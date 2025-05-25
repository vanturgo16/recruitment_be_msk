<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joblist extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function applies()
    {
        return $this->hasMany(\App\Models\JobApply::class, 'id_joblist');
    }

    public function position()
    {
        return $this->belongsTo(\App\Models\MstPosition::class, 'id_position');
    }
}
