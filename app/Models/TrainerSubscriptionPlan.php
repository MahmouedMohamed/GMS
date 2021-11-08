<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSubscriptionPlan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'trainers_subscriptions_plans';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'number_of_sessions',
        'cost',
        'deadline'
    ];
}
