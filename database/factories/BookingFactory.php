<?php

    namespace Database\Factories;

    use App\Models\User;
    use App\Models\WorkshopSession;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class BookingFactory extends Factory
    {
        public function definition(): array
        {
            return [
                'user_id' => User::factory(),
                'session_id' => WorkshopSession::factory(),
                'headcount' => $this->faker->numberBetween(1, 4),
                'payment_ref' => $this->faker->optional()->uuid(),
                'amount_paid' => $this->faker->optional()->randomElement([3000, 5000, 8000, 10000]),
            ];
        }
    }
