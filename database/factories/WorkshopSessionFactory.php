<?php

    namespace Database\Factories;

    use App\Models\Workshop;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class WorkshopSessionFactory extends Factory
    {
        public function definition(): array
        {
            return [
                'workshop_id' => Workshop::factory(),
                'starts_at' => $this->faker->dateTimeBetween('now', '+3 months'),
                'max_capacity' => $this->faker->randomElement([5, 8, 10, 15, 20]),
            ];
        }
    }
