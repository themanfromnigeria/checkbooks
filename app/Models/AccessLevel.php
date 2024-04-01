<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLevel extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'age_start',
        'age_end',
        'borrowing_points_required',
    ];
}
