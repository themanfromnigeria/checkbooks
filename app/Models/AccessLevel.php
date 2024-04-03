<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessLevel extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'age_start',
        'age_end',
        'borrowing_points_required',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
