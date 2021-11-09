<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ClientAlreadySubscribedToGymSubscription;
use App\Exceptions\GymSubscriptionPlanNotFound;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\ControllersTraits\ClientGymSubscriptionValidator;
use App\Traits\ControllersTraits\UserValidator;
use App\Traits\ControllersTraits\GymSubscriptionPlanValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClientGymSubscriptionController extends BaseController
{
    use UserValidator, GymSubscriptionPlanValidator, ClientGymSubscriptionValidator;
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
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        return $this->sendError('Not Implemented', '', 404);
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
            $gymSubscriptionPlan = $this->gymSubscriptionPlanExists($request['gymSubscriptionPlanId']);
            $this->clientAlreadyOnPlan($user);
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
        } catch (ClientAlreadySubscribedToGymSubscription $e) {
            return $this->sendError('Client Already Subscribed to Another Plan');
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
