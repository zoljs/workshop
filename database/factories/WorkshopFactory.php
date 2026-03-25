<?php

    namespace Database\Factories;

    use App\Models\User;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class WorkshopFactory extends Factory
    {
        public function definition(): array
        {
            return [
                'user_id' => User::factory(),
                'name' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'duration_minutes' => $this->faker->randomElement([60, 90, 120, 180]),
                'price_per_person' => $this->faker->randomElement([3000, 5000, 8000, 10000, 15000]),
                'archived' => false,
            ];
        }
    }
