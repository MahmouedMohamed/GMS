<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimeStamps();
    }
    public function assignRole($role)
    {
        $this->roles()->sync($role);  //save if not there, replace if there // can pass argument(x,false) //false will let us add without dropping anything
    }
    public function abilities()
    {
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }
    public function hasAbility(String $ability)
    {
        return $this->abilities($ability);
    }
    public function subscriptionInfo()
    {
        return $this->hasMany(TrainerSubscriptionInfo::class);
    }
    public function gymSubscriptions()
    {
        return $this->hasMany(GymSubscriptionInfo::class);
    }
    public function shifts()
    {
        return $this->hasMany(TrainerShift::class);
    }
    public function clients()
    {
        return $this->hasMany(User::class, 'client_id', 'trainer_id');
    }
    public function trainers()
    {
        return $this->belongsToMany(User::class, 'trainers_clients', 'trainer_id', 'client_id')->withTimestamps();
    }
}
