<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSubscriptionInfo extends Model
{
    use HasFactory;
    protected $table = 'gym_subscriptions_info';
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
        'discount'
    ];

    public function clients()
    {
        return $this->belongsToMany(User::class, 'clients_gym_subscriptions', 'gym_subscription_id', 'client_id')->withTimestamps();
    }
}
