<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
class TrainerShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = ['Sunday','Saturday','Monday','Tuesday','Wednesday','Thursday','Friday'];

        $user = User::inRandomOrder()->first();
        return [
            'id'=>Str::uuid(),
            'trainer_id' => $user->id,
            'day'=>Arr::random($type),
            'from'=>date('H:i:s', rand(1,54000)),
            'to'=>date('H:i:s', rand(1,54000)),
            'created_at'=>$this->faker->dateTimeBetween('-4 week','now'),
            'updated_at'=>$this->faker->dateTimeBetween('-4 week','now'),
        ];
    }
}
