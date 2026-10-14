<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['password_hash'];

    // Cot mat khau trong DB la password_hash
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Bang users khong co cot remember_token
    public function getRememberTokenName()
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role_id, [1, 2]);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }
}
