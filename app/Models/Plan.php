<?php

namespace App\Models;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'duration'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}
