<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSubscriptionPlan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'trainers_subscriptions_plans';
    protected $hidden = ['pivot'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'number_of_sessions',
        'cost',
        'deadline',
        'created_by'
    ];
    public function clients()
    {
        return $this->belongsToMany(User::class, 'clients_trainers_subscriptions', 'subscription_plan_id', 'client_id')
            ->withPivot('session_from', 'session_to', 'left_sessions')
            ->withTimestamps();
    }
}
