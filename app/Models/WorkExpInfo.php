<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExpInfo extends Model
{
    use HasFactory;
    protected $table = 'work_exp_info';
    protected $guarded = ['id'];
}
