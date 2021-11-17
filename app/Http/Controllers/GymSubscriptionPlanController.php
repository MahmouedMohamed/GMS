<?php

namespace App\Http\Controllers;

use App\Models\GymSubscriptionPlan;
use App\Traits\ControllersTraits\GymSubscriptionPlanValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class GymSubscriptionPlanController extends Controller
{
    use GymSubscriptionPlanValidator;

    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create']]);

        $this->middleware('auth', ['only' => ['edit']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(
            'Gym.Subscriptions.index',
            [
                'plans' => Cache::remember('gym-subscriptions-plans', 60 * 60 * 24, function () {
                    return GymSubscriptionPlan::select(
                        'id as ID',
                        'name as Name',
                        'number_of_months as NumberofMonths',
                        'cost as Cost',
                        DB::raw('FORMAT(cost - (cost * NVL(discount,0) /100), 2) AS \'CostAfterDiscount\''),
                        DB::raw('NVL(CONCAT(discount,\'%\'),\'0%\') AS Discount'),
                        DB::raw('CASE WHEN `discount` IS NULL OR `discount` = 0 THEN false ELSE true END AS \'HasDiscount\''),
                    )->orderBy('NumberofMonths')->get();
                })
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Gym.Subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateGymSubscriptionPlanData($request, 'store');
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        GymSubscriptionPlan::create([
            'id' => Str::uuid(),
            'name' => strval($request['numberOfMonths']) . ' Months Offer',
            'number_of_months' => $request['numberOfMonths'],
            'cost' => $request['cost'],
            'discount' => $request['discount'],
            'created_by' => Auth()->user()->id,
        ]);
        return redirect()->route('gym.subscriptions.index')->with('status', 'Gym Subscription Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GymSubscriptionPlan  $gymSubscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function show(GymSubscriptionPlan $subscription)
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
        return view('Gym.Subscriptions.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GymSubscriptionPlan  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GymSubscriptionPlan $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GymSubscriptionPlan  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(GymSubscriptionPlan $subscription)
    {
        //
    }
    private function validateGymSubscriptionPlan()
    {
        return request()->validate([
            'numberOfMonths' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:1',
            'discount' => 'required|numeric|min:0|max:100',
        ]);
    }
}
