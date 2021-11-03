<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\GymSubscriptionInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GymSubscriptionInfoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
