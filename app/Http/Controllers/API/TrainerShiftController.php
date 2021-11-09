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
        return TrainerShift::
            //     select(
            //     'id as ID',
            //     'name as Name',
            //     'number_of_months as Number of Months',
            //     'cost as Cost',
            //     DB::raw('FORMAT(cost - (cost * NVL(discount,0) /100), 2) AS \'Cost After Discount\''),
            //     DB::raw('NVL(CONCAT(discount,\'%\'),\'0%\') AS Discount'),
            //     DB::raw('CASE WHEN `discount` IS NULL OR `discount` = 0 THEN false ELSE true END AS \'Has Discount\''),
            // )->
            get();
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
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return \Illuminate\Http\Response
     */
    public function show(TrainerShift $trainerShift)
    {
        //
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
    public function destroy(TrainerShift $trainerShift)
    {
        //
    }
    public function validateShiftData(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'day' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
                    'from' => 'required|date_format:h:i:s',
                    'to' => 'required|date_format:h:i:s',
                ];
                break;
            case 'update':
                $rules = [
                    'day' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
                    'from' => 'required|date',
                    'to' => 'required|date',
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
