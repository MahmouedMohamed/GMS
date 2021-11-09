<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSubscriptionPlan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $hidden = ['pivot'];
    protected $table = 'gym_subscriptions_plans';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'number_of_months',
        'cost',
        'discount',
        'created_by'
    ];

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'clients_gym_subscriptions', 'gym_subscription_plan_id', 'client_id')->withPivot('start','end')->withTimestamps();
        // return $this->hasMany(User::class,'client_id','gym_subscription_plan_id');
    }
}
