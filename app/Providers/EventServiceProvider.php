<?php

namespace App\Providers;

use App\Models\GymSubscriptionPlan;
use App\Models\TrainerShift;
use App\Models\TrainerSubscriptionPlan;
use App\Observers\GymSubscriptionPlanObserver;
use App\Observers\TrainerShiftObserver;
use App\Observers\TrainerSubscriptionPlanObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        GymSubscriptionPlan::observe(GymSubscriptionPlanObserver::class);

        TrainerSubscriptionPlan::observe(TrainerSubscriptionPlanObserver::class);
        TrainerShift::observe(TrainerShiftObserver::class);
    }
}
