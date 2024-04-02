<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Author;
use App\Models\Profile;
use App\Models\AccessLevel;
use App\Models\Subscription;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'access_level'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Lendings associated with the user
    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Returns a single active subscription (if any)
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->whereNull('end_date')->orderBy('created_at', 'desc');
    }

    // Active lendings associated with the user
    public function activeLendings()
    {
        return $this->lendings()->whereNull('returned_at');
    }

    public function hasRole(string $role): bool
    {
        return $this->getAttribute('role') === $role;
    }

    // Checks if the user has any active subscriptions.
    public function hasActiveSubscription()
    {
        return $this->subscriptions()->where('end_date', '>=', now())->get();
    }

    public function subscriptionHistory()
    {
        return $this->subscriptions()->where('end_date', '<', now())->get();
    }

    public function accessLevel()
    {
        return $this->belongsTo(AccessLevel::class);
    }
}
