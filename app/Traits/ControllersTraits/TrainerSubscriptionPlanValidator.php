<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\TrainerSubscriptionPlanNotFound;
use App\Models\TrainerSubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ValidatorLanguagesSupport;

trait TrainerSubscriptionPlanValidator
{
    use ValidatorLanguagesSupport;
    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function TrainerSubscriptionPlanExists($id)
    {
        $trainerSubscriptionPlan = TrainerSubscriptionPlan::find($id);
        if (!$trainerSubscriptionPlan)
            throw new TrainerSubscriptionPlanNotFound();
        return $trainerSubscriptionPlan;
    }

    public function validateTrainerSubscriptionPlanData(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'numberOfSessions' => 'required|integer|min:1',
                    'cost' => 'required|numeric|min:1',
                    'deadline' => 'required|integer|min:1|max:44', //44 Weeks in the year
                ];
                break;
            case 'update':
                $rules = [
                    'numberOfSessions' => 'required|integer|min:1',
                    'cost' => 'required|numeric|min:1',
                    'deadline' => 'required|integer|min:1|max:44', //44 Weeks in the year
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
