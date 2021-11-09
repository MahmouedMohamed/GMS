<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\GymSubscriptionPlanNotFound;
use App\Models\GymSubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ValidatorLanguagesSupport;

trait GymSubscriptionPlanValidator
{
    use ValidatorLanguagesSupport;
    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function gymSubscriptionPlanExists($id)
    {
        $gymSubscriptionPlan = GymSubscriptionPlan::find($id);
        if (!$gymSubscriptionPlan)
            throw new GymSubscriptionPlanNotFound();
        return $gymSubscriptionPlan;
    }

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
