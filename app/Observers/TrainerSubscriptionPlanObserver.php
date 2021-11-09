<?php

namespace App\Observers;

use App\Models\TrainerSubscriptionPlan;
use Illuminate\Support\Facades\Cache;

class TrainerSubscriptionPlanObserver
{
    /**
     * Handle the TrainerSubscriptionPlan "created" event.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return void
     */
    public function created(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        Cache::forget('trainers-subscriptions-plans');
    }

    /**
     * Handle the TrainerSubscriptionPlan "updated" event.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return void
     */
    public function updated(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        Cache::forget('trainers-subscriptions-plans');
    }

    /**
     * Handle the TrainerSubscriptionPlan "deleted" event.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return void
     */
    public function deleted(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        Cache::forget('trainers-subscriptions-plans');
    }

    /**
     * Handle the TrainerSubscriptionPlan "restored" event.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return void
     */
    public function restored(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        Cache::forget('trainers-subscriptions-plans');
    }

    /**
     * Handle the TrainerSubscriptionPlan "force deleted" event.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return void
     */
    public function forceDeleted(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        Cache::forget('trainers-subscriptions-plans');
    }
}
