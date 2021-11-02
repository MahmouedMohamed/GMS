<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerShift extends Model
{
    use HasFactory;
    public function trainer()
    {
        return $this->belongsTo(User::class);
    }
}
