<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\ClientAlreadySubscribedToTrainerSubscription;
use App\Exceptions\ClientTrainerSubscriptionNotFound;
use App\Models\User;
use App\Traits\ValidatorLanguagesSupport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ClientTrainerSubscriptionValidator
{
    use ValidatorLanguagesSupport;
    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function clientTrainerSubscriptionExists($id)
    {
        $clientTrainerSubscribedPlan = DB::table('clients_trainers_subscriptions')
            ->where('id', '=', $id);
        if (!$clientTrainerSubscribedPlan->first())
            throw new ClientTrainerSubscriptionNotFound();
        return $clientTrainerSubscribedPlan;
    }
    public function clientAlreadyOnPlan(User $user)
    {
        $subscribedPlan = DB::table('clients_trainers_subscriptions')
            ->where('client_id', '=', $user->id)
            ->where('left_sessions', '>=', 1)->first();
        if ($subscribedPlan)
            throw new ClientAlreadySubscribedToTrainerSubscription();
    }
    public function trainerIsAvailable(User $user)
    {
        //ToDo: Check if trainer has free session time
    }
}
