<?php

namespace App\Http\Controllers\API;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\TrainerShift;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TrainerShiftController extends BaseController
{
    use UserValidator;
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
            $validated = $this->validateShiftData($request, 'store');
            if ($validated->fails()) {
                return $this->sendError('InvalidData', $validated->messages(), 400);
            }
            return $this->sendResponse($user->shifts()->create([
                'id' => Str::uuid(),
                'day' => $request['day'],
                'from' => $request['from'],
                'to' => $request['to'],
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
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainerShift $trainerShift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
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
