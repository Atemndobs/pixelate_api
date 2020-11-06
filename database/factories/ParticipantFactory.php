<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Participant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'=> $this->faker->uuid,
           // 'user_id'=>User::factory(),
            'chat_id' => Chat::factory(),
        ];
    }
}
