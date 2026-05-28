<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sitterProfile(): HasOne
    {
        return $this->hasOne(SitterProfile::class);
    }

    public function bookingsAsOwner(): HasMany
    {
        return $this->hasMany(Booking::class, 'owner_id');
    }

    public function bookingsAsSitter(): HasMany
    {
        return $this->hasMany(Booking::class, 'sitter_id');
    }

    public function receivedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'sitter_id');
    }
}
