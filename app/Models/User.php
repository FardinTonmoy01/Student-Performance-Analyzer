<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'photo', 'mobile_no', 'password', 'type', 'address', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasRole($role)
    {
        $roleId = 0;
        if ($role == 'super_admin') {
            $roleId = 1;
        } else if ($role == 'admin') {
            $roleId = 2;
        } else if ($role == 'teacher') {
            $roleId = 3;
        } else if ($role == 'teacher') {
            $roleId = 4;
        } else {
            $roleId = 5;
        }
        return $this->type === $roleId ? true : false;
    }


    public function students ()
    {
        return $this->hasMany(Student::class, 'user_id');
    }
}
