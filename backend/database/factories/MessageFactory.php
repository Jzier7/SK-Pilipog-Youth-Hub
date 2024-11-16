<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userIds = [34, 32];

        $senderId = $this->faker->randomElement($userIds);

        return [
            'sender_id' => $senderId,
            'content' => $this->faker->text(200),
            'read' => $this->faker->boolean(50),
        ];
    }
}

