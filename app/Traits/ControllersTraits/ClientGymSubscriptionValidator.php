<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\ClientAlreadySubscribedToGymSubscription;
use App\Exceptions\ClientGymSubscriptionNotFound;
use App\Models\User;
use App\Traits\ValidatorLanguagesSupport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ClientGymSubscriptionValidator
{
    use ValidatorLanguagesSupport;
    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function clientGymSubscriptionExists($id)
    {
        $clientGymSubscribedPlan =  DB::table('clients_gym_subscriptions')
            ->where('id', '=', $id);
        if (!$clientGymSubscribedPlan->first())
            throw new ClientGymSubscriptionNotFound();
        return $clientGymSubscribedPlan;
    }
    public function clientAlreadyOnPlan(User $user)
    {
        $subscribedPlan =  DB::table('clients_gym_subscriptions')
            ->where('client_id', '=', $user->id)
            ->where('end', '>=', Carbon::now())->first();
        if ($subscribedPlan)
            throw new ClientAlreadySubscribedToGymSubscription();
    }
}
