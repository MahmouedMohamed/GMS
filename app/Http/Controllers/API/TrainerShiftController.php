<?php

namespace App\Http\Controllers\API;

use App\Exceptions\TrainerShiftNotFound;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\TrainerShift;
use App\Traits\ControllersTraits\TrainerShiftValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TrainerShiftController extends BaseController
{
    use UserValidator, TrainerShiftValidator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentPage = request()->get('page', 1);
        return $this->sendResponse(
            Cache::remember('trainers-shifts-' . $currentPage, 60 * 60 * 24, function () {
                return TrainerShift::join('users', 'users.id', 'trainers_shifts.trainer_id')
                    ->select(
                        'trainers_shifts.id AS ID',
                        'trainers_shifts.day',
                        'trainers_shifts.from',
                        'trainers_shifts.to',
                        'users.id AS Trainer ID',
                        'users.name AS Trainer Name',
                        'users.gender AS Trainer Gender',
                        'users.phone_number AS Trainer Phone Number',
                    )
                    ->oldest('trainers_shifts.day')
                    ->paginate(8);
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
            $trainer = $this->userExists($request['trainerId']);
            $validated = $this->validateTrainerShiftData($request, 'store');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            return $this->sendResponse($trainer->shifts()->create([
                'id' => Str::uuid(),
                'day' => $request['day'],
                'from' => $request['from'],
                'to' => $request['to'],
            ]), 'Data Created Successfully');
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
        try {
            $trainerShift = $this->TrainerShiftExists($id);
            return $this->sendResponse($trainerShift, 'Data Retrieved Successfully');
        } catch (TrainerShiftNotFound $e) {
            return $this->sendError('Trainer Shift Not Found');
        }
    }

    /**
     * Display a specified resource related to Trainer.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function getSpecificTrainerShifts(String $id)
    {
        try {
            //ToDo: For Authorization
            $user = $this->userExists($id);
            return $this->sendResponse($user->shifts, 'Data Retrieved Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
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
            $trainerShift = $this->TrainerShiftExists($id);
            $validated = $this->validateTrainerShiftData($request, 'update');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            $trainerShift->update([
                'day' => $request['day'],
                'from' => $request['from'],
                'to' => $request['to'],
            ]);
            return $this->sendResponse('', 'Data Updated Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        } catch (TrainerShiftNotFound $e) {
            return $this->sendError('Trainer Shift Not Found');
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
            $trainerShift = $this->TrainerShiftExists($id);
            $trainerShift->delete();
            return $this->sendResponse('', 'Data Deleted Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Doesn\'t Exist');
        } catch (TrainerShiftNotFound $e) {
            return $this->sendError('Trainer Shift Not Found');
        }
    }
}
