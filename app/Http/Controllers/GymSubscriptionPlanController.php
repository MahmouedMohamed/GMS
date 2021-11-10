<?php

namespace App\Http\Controllers;

use App\Models\GymSubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GymSubscriptionPlanController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function show(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(GymSubscriptionPlan $gymSubscriptionPlan)
    {
        //
    }
}
