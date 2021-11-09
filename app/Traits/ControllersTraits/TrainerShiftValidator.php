<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\TrainerShiftNotFound;
use App\Models\TrainerShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ValidatorLanguagesSupport;

trait TrainerShiftValidator
{
    use ValidatorLanguagesSupport;
    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function TrainerShiftExists($id)
    {
        $trainerShift = TrainerShift::find($id);
        if (!$trainerShift)
            throw new TrainerShiftNotFound();
        return $trainerShift;
    }

    public function validateTrainerShiftData(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'day' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
                    'from' => 'required|date_format:H:i',
                    'to' => 'required|date_format:H:i',
                ];
                break;
            case 'update':
                $rules = [
                    'day' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
                    'from' => 'required|date_format:H:i',
                    'to' => 'required|date_format:H:i',
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
