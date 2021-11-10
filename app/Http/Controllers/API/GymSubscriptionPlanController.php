<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GymSubscriptionPlanNotFound;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\GymSubscriptionPlan;
use App\Traits\ControllersTraits\GymSubscriptionPlanValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GymSubscriptionPlanController extends BaseController
{
    use UserValidator, GymSubscriptionPlanValidator;
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
        try {
            $user = $this->userExists($request['userId']);
            $validated = $this->validateGymSubscriptionPlanData($request, 'store');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            return $this->sendResponse(GymSubscriptionPlan::create([
                'id' => Str::uuid(),
                'name' => strval($request['numberOfMonths']) . ' Months Offer',
                'number_of_months' => $request['numberOfMonths'],
                'cost' => $request['cost'],
                'discount' => $request['discount'],
                'created_by' => $user->id
            ]), 'Data Created Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        try {
            $gymSubscriptionPlan = $this->gymSubscriptionPlanExists($id);
            return $this->sendResponse($gymSubscriptionPlan, 'Data Retrieved Successfully');
        } catch (GymSubscriptionPlanNotFound $e) {
            return $this->sendError('Gym Subscription Plan Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String $id)
    {
        try {
            //ToDo: For Authorization
            $user = $this->userExists($request['userId']);
            $gymSubscriptionPlan = $this->gymSubscriptionPlanExists($id);
            $validated = $this->validateGymSubscriptionPlanData($request, 'update');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            $gymSubscriptionPlan->update([
                'name' => strval($request['numberOfMonths']) . ' Months Offer',
                'number_of_months' => $request['numberOfMonths'],
                'cost' => $request['cost'],
                'discount' => $request['discount'],
            ]);
            return $this->sendResponse("", 'Data Updated Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        } catch (GymSubscriptionPlanNotFound $e) {
            return $this->sendError('Gym Subscription Plan Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, String $id)
    {
        try {
            //ToDo: For Authorization
            $user = $this->userExists($request['userId']);
            $gymSubscriptionPlan = $this->gymSubscriptionPlanExists($id);
            $gymSubscriptionPlan->delete();
            return $this->sendResponse('', 'Data Deleted Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        } catch (GymSubscriptionPlanNotFound $e) {
            return $this->sendError('Gym Subscription Plan Not Found');
        }
    }
}
