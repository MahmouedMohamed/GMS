<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ClientAlreadySubscribedToTrainerSubscription;
use App\Exceptions\TrainerSubscriptionPlanNotFound;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\ControllersTraits\ClientTrainerSubscriptionValidator;
use App\Traits\ControllersTraits\TrainerSubscriptionPlanValidator;
use App\Traits\ControllersTraits\UserValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientTrainerSubscriptionController extends BaseController
{
    use UserValidator, TrainerSubscriptionPlanValidator, ClientTrainerSubscriptionValidator;
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
                DB::table('clients_trainers_subscriptions')
                    ->join('users AS clients', 'clients.id', '=', 'clients_trainers_subscriptions.client_id')
                    ->join('users AS trainers', 'trainers.id', '=', 'clients_trainers_subscriptions.trainer_id')
                    ->select(
                        'clients_trainers_subscriptions.id AS ClientTrainerSubscriptionId',
                        'clients_trainers_subscriptions.session_from AS SessionFrom',
                        'clients_trainers_subscriptions.session_to AS SessionTo',
                        'clients_trainers_subscriptions.left_sessions AS LeftSessions',
                        'clients.id AS ClientId',
                        'clients.name AS ClientName',
                        'clients.email AS ClientEmail',
                        'clients.phone_number AS ClientPhoneNumber',
                        'trainers.id AS TrainerId',
                        'trainers.name AS TrainerName',
                        'trainers.email AS TrainerEmail',
                        'trainers.phone_number AS TrainerPhoneNumber',
                    )
                    ->get(),
                'Trainers Subscriptions Retrieved Successfully'
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
            $client = $this->userExists($request['clientId']);
            $trainer = $this->userExists($request['trainerId']);
            $trainerSubscriptionPlan = $this->trainerSubscriptionPlanExists($request['trainerSubscriptionPlanId']);
            $this->clientAlreadyOnPlan($client);
            $this->trainerIsAvailable($trainer);
            $client->trainersSubscriptionPlans()
                ->attach(
                    $request['trainerSubscriptionPlanId'],
                    array(
                        'id' => Str::uuid(),
                        'trainer_id' => $trainer->id,
                        'left_sessions' => $trainerSubscriptionPlan->number_of_sessions,
                        'session_from' => Carbon::now(),
                        'session_to' => Carbon::now()->addMonths($trainerSubscriptionPlan->deadline)
                    )
                );
            return $this->sendResponse('', 'You have Subscribed Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (TrainerSubscriptionPlanNotFound $e) {
            return $this->sendError('Trainer Subscription Plan Not Found');
        } catch (ClientAlreadySubscribedToTrainerSubscription $e) {
            return $this->sendError('Client Already Subscribed to Another Plan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->sendError('Not Implemented', '', 404);
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
        //ToDo: Implement
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
        //ToDo: Implement
    }
}
