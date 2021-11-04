<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\GymSubscriptionInfo;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GymSubscriptionInfoController extends BaseController
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
            Cache::remember('gym-subscription-info', 60 * 60 * 24, function () {
                return GymSubscriptionInfo::select(
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
        return $this->sendResponse(GymSubscriptionInfo::create([
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
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return \Illuminate\Http\Response
     */
    public function show(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GymSubscriptionInfo $gymSubscriptionInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GymSubscriptionInfo  $gymSubscriptionInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(GymSubscriptionInfo $gymSubscriptionInfo)
    {
        //
    }
}
