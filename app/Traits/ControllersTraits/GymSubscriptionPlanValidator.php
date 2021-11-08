<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\GymSubscriptionPlanNotFound;
use App\Models\GymSubscriptionPlan;

trait GymSubscriptionPlanValidator
{

    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function GymSubscriptionPlanExists($id)
    {
        $user = GymSubscriptionPlan::find($id);
        if (!$user)
            throw new GymSubscriptionPlanNotFound();
        return $user;
    }
}
