<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainProfile extends Model
{
    use HasFactory;
    protected $table = 'main_profile';
    protected $guarded = ['id'];
}
