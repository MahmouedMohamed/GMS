<?php

namespace App\Observers;

use App\Models\GymSubscriptionInfo;
use Illuminate\Support\Facades\Cache;

class GymSubscriptionInfoObserver
{
    /**
     * Handle the GymSubscriptionInfo "created" event.
     *
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return void
     */
    public function created(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        Cache::forget('gym-subscription-info');
    }

    /**
     * Handle the GymSubscriptionInfo "updated" event.
     *
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return void
     */
    public function updated(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        Cache::forget('gym-subscription-info');
    }

    /**
     * Handle the GymSubscriptionInfo "deleted" event.
     *
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return void
     */
    public function deleted(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        Cache::forget('gym-subscription-info');
    }

    /**
     * Handle the GymSubscriptionInfo "restored" event.
     *
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return void
     */
    public function restored(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        Cache::forget('gym-subscription-info');
    }

    /**
     * Handle the GymSubscriptionInfo "force deleted" event.
     *
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return void
     */
    public function forceDeleted(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        Cache::forget('gym-subscription-info');
    }
}
