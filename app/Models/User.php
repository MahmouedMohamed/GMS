<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Uuids;

class User extends Authenticatable
{
    use HasFactory;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'user_name',
        'email',
        'password',
        'gender',
        'phone_number',
        'address',
        'nationality',
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
    public function gymSubscriptionPlans()
    {
        return $this->belongsToMany(GymSubscriptionPlan::class, 'clients_gym_subscriptions', 'client_id', 'gym_subscription_plan_id')
            ->withPivot('start', 'end')
            ->withTimestamps();
    }
    public function shifts()
    {
        return $this->hasMany(TrainerShift::class,'trainer_id');
    }
    public function clients()
    {
        return $this->belongsToMany(User::class, 'trainers_clients', 'client_id', 'trainer_id')
            ->withPivot('session_from', 'session_to', 'left_sessions', 'session_done', 'client_note', 'trainer_note')
            ->withTimestamps();
    }
    public function trainers()
    {
        return $this->belongsToMany(User::class, 'trainers_clients', 'trainer_id', 'client_id')
            ->withPivot('session_from', 'session_to', 'left_sessions', 'session_done', 'client_note', 'trainer_note')
            ->withTimestamps();
    }
    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class);
    }
    public function createAccessToken()
    {
        $this->deleteRelatedAccessTokens();
        //Hash::make() -> saves only 60 chars to database
        //TODO: Solve & extend to 255 chars
        $accessToken = Str::random(60);
        $expiryDate = Carbon::now('GMT+2')->addMonth();
        $this->accessTokens()->create([
            'id' => Str::uuid(),
            'access_token' => Hash::make($accessToken),
            'scopes' => '[]',
            'active' => 1,
            'expires_at' => $expiryDate,

        ]);
        return ['accessToken' => $accessToken, 'expiryDate' => $expiryDate];
    }
    public function deleteRelatedAccessTokens()
    {
        $this->accessTokens()->delete();
    }
}
