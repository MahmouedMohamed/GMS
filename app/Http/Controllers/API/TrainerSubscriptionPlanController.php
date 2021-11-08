<?php

namespace App\Http\Controllers\API;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\trainerSubscriptionPlan;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TrainerSubscriptionPlanController extends BaseController
{
    use UserValidator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // $user = $this->userExists($request['userId']);
            $validated = $this->validateShiftData($request, 'store');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            return $this->sendResponse(TrainerSubscriptionPlan::create([
                'id' => Str::uuid(),
                'number_of_sessions' => $request['number_of_sessions'],
                'cost' => $request['cost'],
                'deadline' => $request['deadline'],
            ]), 'Data Created Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('UserNotFound');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function show(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainerSubscriptionPlan  $trainerSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainerSubscriptionPlan $trainerSubscriptionPlan)
    {
        //
    }
    public function validateShiftData(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'number_of_sessions' => 'required|integer|min:1',
                    'cost' => 'required|numeric|min:1',
                    'deadline' => 'required|integer|min:1|max:44', //44 Weeks in the year
                ];
                break;
            case 'update':
                $rules = [
                    'number_of_sessions' => 'required|integer|min:1',
                    'cost' => 'required|numeric|min:1',
                    'deadline' => 'required|integer|min:1|max:44', //44 Weeks in the year
                ];
                break;
        }
        $messages = [];
        // if ($request['language'] != null)
        //     $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }

    public function getValidatorMessagesBasedOnLanguage(string $language)
    {
        if ($language == 'En')
            return [
                'required' => 'This field is required',
                'min' => 'Wrong value, minimum value is :min',
                'max' => 'Wrong value, maximum value is :max',
                'integer' => 'Wrong value, supports only real numbers',
                'in' => 'Wrong value, supported values are :values',
                'numeric' => 'Wrong value, supports only numeric numbers',
            ];
        else if ($language == 'Ar')
            return [
                'required' => 'هذا الحقل مطلوب',
                'min' => 'قيمة خاطئة، أقل قيمة هي :min',
                'max' => 'قيمة خاطئة أعلي قيمة هي :max',
                'integer' => 'قيمة خاطئة، فقط يمكن قبول الأرقام فقط',
                'in' => 'قيمة خاطئة، القيم المتاحة هي :values',
                'image' => 'قيمة خاطئة، يمكن قبول الصور فقط',
                'mimes' => 'يوجد خطأ في النوع، الأنواع المتاحة هي :values',
                'numeric' => 'قيمة خاطئة، يمكن قبول الأرقام فقط',
            ];
    }
}
