<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //  'id' => $faker->randomNumber(2, true),
            // 'user_id' => factory(\App\Models\User::class)->create()?factory(\App\Models\User::class)->create():factory(\App\Models\User::class)->create(),
            'user_id' => $this->faker->numberBetween(1,10),
            'comment' => $this->faker->realText(50),
            'commentable_type' => "App\Models\Design",
            'commentable_id' => $this->faker->numberBetween(1,10),
        ];
    }
}
