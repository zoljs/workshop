<?php

    namespace Database\Seeders;

    use App\Models\User;
    use App\Models\Workshop;
    use App\Models\WorkshopSession;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        public function run(): void
        {
            $this->call(RoleSeeder::class);

            // Create an admin user
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@workshop.test',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('admin');

            // Create an instructor
            $instructor = User::factory()->create([
                'name' => 'Test Instructor',
                'email' => 'instructor@workshop.test',
                'password' => bcrypt('password'),
            ]);
            $instructor->assignRole('instructor');

            // Create some regular users
            User::factory(10)->create()->each(fn($u) => $u->assignRole('user'));

            // Create workshops with sessions and bookings
            Workshop::factory(8)
                ->create(['user_id' => $instructor->id])
                ->each(function ($workshop) {
                    WorkshopSession::factory(3)
                        ->create(['workshop_id' => $workshop->id]);
                });
        }
    }
