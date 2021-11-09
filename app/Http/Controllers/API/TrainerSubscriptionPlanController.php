<?php

namespace App\Http\Controllers\API;

use App\Exceptions\TrainerSubscriptionPlanNotFound;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\trainerSubscriptionPlan;
use App\Traits\ControllersTraits\TrainerSubscriptionPlanValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainerSubscriptionPlanController extends BaseController
{
    use UserValidator, TrainerSubscriptionPlanValidator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(
            Cache::remember('trainers-subscriptions-plans', 60 * 60 * 24, function () {
                return
                    TrainerSubscriptionPlan
                    ::select(
                        'id as ID',
                        'number_of_sessions as Number of Sessions',
                        'cost as Cost',
                        DB::raw('CONCAT(deadline,\' Weeks\') AS Deadline'),
                        DB::raw('CONCAT(deadline,CASE WHEN `deadline` IS NULL OR `deadline` = 1 THEN \' Week\' ELSE \' Weeks\' END) AS Deadline'),
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
            $validated = $this->validateTrainerSubscriptionPlanData($request, 'store');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            return $this->sendResponse(TrainerSubscriptionPlan::create([
                'id' => Str::uuid(),
                'number_of_sessions' => $request['numberOfSessions'],
                'cost' => $request['cost'],
                'deadline' => $request['deadline'],
                'created_by' => $user->id
            ]), 'Data Created Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('UserNotFound');
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
            $trainerSubscriptionPlan = $this->TrainerSubscriptionPlanExists($id);
            return $this->sendResponse($trainerSubscriptionPlan, 'Data Retrieved Successfully');
        } catch (TrainerSubscriptionPlanNotFound $e) {
            return $this->sendError('Trainer Subscription Plan Not Found');
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
            $trainerSubscriptionPlan = $this->TrainerSubscriptionPlanExists($id);
            $validated = $this->validateTrainerSubscriptionPlanData($request, 'update');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            $trainerSubscriptionPlan->update([
                'number_of_sessions' => $request['numberOfSessions'],
                'cost' => $request['cost'],
                'deadline' => $request['deadline'],
            ]);
            return $this->sendResponse('', 'Data Updated Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        } catch (TrainerSubscriptionPlanNotFound $e) {
            return $this->sendError('Trainer Subscription Plan Not Found');
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
            $trainerSubscriptionPlan = $this->TrainerSubscriptionPlanExists($id);
            $trainerSubscriptionPlan->delete();
            return $this->sendResponse('', 'Data Deleted Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        } catch (TrainerSubscriptionPlanNotFound $e) {
            return $this->sendError('Trainer Subscription Plan Not Found');
        }
    }
}
