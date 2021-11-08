<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GymSubscriptionPlanNotFound;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\ControllersTraits\UserValidator;
use App\Traits\ControllersTraits\GymSubscriptionPlanValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ClientGymSubscriptionController extends BaseController
{
    use UserValidator, GymSubscriptionPlanValidator;
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            return $this->sendResponse(
                $user->gymSubscriptionPlans()
                    ->select('clients_gym_subscriptions.id','gym_subscriptions_plans.id as gym_subscription_plan_id', 'name', 'start', 'number_of_months', 'end')
                    ->get(),
                'Gym Subscriptions Retrieved Successfully'
            );
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        }
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
            $gymSubscriptionPlan = $this->GymSubscriptionPlanExists($request['gymSubscriptionPlanId']);
            $user->gymSubscriptionPlans()
                ->attach(
                    $request['gymSubscriptionPlanId'],
                    array(
                        'id' => Str::uuid(),
                        'start' => Carbon::now(),
                        'end' => Carbon::now()->addMonths($gymSubscriptionPlan->number_of_months)
                    )
                );
            return $this->sendResponse('', 'You have Subscribed Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (GymSubscriptionPlanNotFound $e) {
            return $this->sendError('Gym Subscription Plan Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
