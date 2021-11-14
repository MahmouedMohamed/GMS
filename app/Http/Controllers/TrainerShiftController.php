<?php

namespace App\Http\Controllers;

use App\Models\TrainerShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrainerShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Trainer.Shifts.index', [
            'shifts' => Cache::remember('TrainerShifts', 60 * 60 * 24, function () {
                return TrainerShift::with('trainer:id,name')->get();
            })
        ]);
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
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return \Illuminate\Http\Response
     */
    public function show(TrainerShift $trainerShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainerShift $trainerShift)
    {
        //
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
}
