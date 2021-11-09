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

    public function validateGymSubscriptionPlanData(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'numberOfMonths' => 'required|integer|min:1',
                    'cost' => 'required|numeric|min:1',
                    'discount' => 'required|numeric|min:0|max:100',
                ];
                break;
            case 'update':
                $rules = [
                    'numberOfMonths' => 'required|integer|min:1',
                    'cost' => 'required|numeric|min:1',
                    'discount' => 'required|numeric|min:0|max:100',
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
