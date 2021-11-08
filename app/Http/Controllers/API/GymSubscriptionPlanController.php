<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\GymSubscriptionPlan;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GymSubscriptionPlanController extends BaseController
{
    use UserValidator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(
            Cache::remember('gym-subscriptions-plans', 60 * 60 * 24, function () {
                return GymSubscriptionPlan::select(
                    'id as ID',
                    'name as Name',
                    'number_of_months as Number of Months',
                    'cost as Cost',
                    DB::raw('FORMAT(cost - (cost * NVL(discount,0) /100), 2) AS \'Cost After Discount\''),
                    DB::raw('NVL(CONCAT(discount,\'%\'),\'0%\') AS Discount'),
                    DB::raw('CASE WHEN `discount` IS NULL OR `discount` = 0 THEN false ELSE true END AS \'Has Discount\''),
                )->get();
            }),
            'Data Retrieved Successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $numberOfMonths = random_int(1, 12);
        $cost = random_int(100, 1000);
        $discount = random_int(1, 100);
        return $this->sendResponse(GymSubscriptionPlan::create([
            'id' => Str::uuid(),
            'name' => strval($numberOfMonths) . ' Months Offer',
            'number_of_months' => $numberOfMonths,
            'cost' => $cost,
            'discount' => $discount,
        ]), 'Data Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function show(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }
    public function validateShiftData(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'name' => 'string',
                    'number_of_months' => 'required|integer',
                    'cost' => 'required|numeric|min:1',
                    'discount' => 'required|numeric|min:0|max:100'
                ];
                break;
            case 'update':
                $rules = [
                    'name' => 'string',
                    'number_of_months' => 'required|integer',
                    'cost' => 'required|numeric|min:1',
                    'discount' => 'required|numeric|min:0|max:100'
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
