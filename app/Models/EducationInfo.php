<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationInfo extends Model
{
    use HasFactory;
    protected $table = 'education_info';
    protected $guarded = ['id'];
}
