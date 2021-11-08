<?php

namespace App\Observers;

use App\Models\GymSubscriptionPlan;
use Illuminate\Support\Facades\Cache;

class GymSubscriptionPlanObserver
{
    /**
     * Handle the GymSubscriptionPlan "created" event.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return void
     */
    public function created(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        Cache::forget('gym-subscriptions-plans');
    }

    /**
     * Handle the GymSubscriptionPlan "updated" event.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return void
     */
    public function updated(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        Cache::forget('gym-subscriptions-plans');
    }

    /**
     * Handle the GymSubscriptionPlan "deleted" event.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return void
     */
    public function deleted(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        Cache::forget('gym-subscriptions-plans');
    }

    /**
     * Handle the GymSubscriptionPlan "restored" event.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return void
     */
    public function restored(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        Cache::forget('gym-subscriptions-plans');
    }

    /**
     * Handle the GymSubscriptionPlan "force deleted" event.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return void
     */
    public function forceDeleted(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        Cache::forget('gym-subscriptions-plans');
    }
}
