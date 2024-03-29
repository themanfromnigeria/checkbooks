<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Lending;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'edition',
        'description',
        'prologue',
        'publisher',
        'publication_date',
        'isbn',
        'price',
        'status',
        'is_borrowed',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Lendings associated with the book
    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }

    // If the book is currently borrowed.
    public function isBorrowed()
    {
        return $this->lendings()->whereNull('returned_at')->exists();
    }
}
