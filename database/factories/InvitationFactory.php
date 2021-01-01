<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'=>  $this->faker->randomNumber(3),
            'recipient_email' => $this->faker->email,
            'sender_id' => User::factory(),
            'team_id' => Team::factory(),
            'token' => $this->faker->unique()->uuid,
        ];
    }
}
