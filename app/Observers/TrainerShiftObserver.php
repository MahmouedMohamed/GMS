<?php

namespace App\Observers;

use App\Models\TrainerShift;
use Illuminate\Support\Facades\Cache;

class TrainerShiftObserver
{
    private function clearCache()
    {
        $index = 1;
        while (true) {
            if (Cache::has('trainers-shifts-' . $index)) {
                Cache::forget('trainers-shifts-' . $index);
                $index++;
            } else
                break;
        }
        return;
    }
    /**
     * Handle the TrainerShift "created" event.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return void
     */
    public function created(TrainerShift $trainerShift)
    {
        $this->clearCache();
    }

    /**
     * Handle the TrainerShift "updated" event.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return void
     */
    public function updated(TrainerShift $trainerShift)
    {
        $this->clearCache();
    }

    /**
     * Handle the TrainerShift "deleted" event.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return void
     */
    public function deleted(TrainerShift $trainerShift)
    {
        $this->clearCache();
    }

    /**
     * Handle the TrainerShift "restored" event.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return void
     */
    public function restored(TrainerShift $trainerShift)
    {
        $this->clearCache();
    }

    /**
     * Handle the TrainerShift "force deleted" event.
     *
     * @param  \App\Models\TrainerShift  $trainerShift
     * @return void
     */
    public function forceDeleted(TrainerShift $trainerShift)
    {
        $this->clearCache();
    }
}
