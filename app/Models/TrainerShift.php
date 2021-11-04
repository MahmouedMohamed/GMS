<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerShift extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table ='trainers_shifts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'day',
        'from',
        'to',
        'trainer_id'
    ];
    public function trainer()
    {
        return $this->belongsTo(User::class,);
    }
}
