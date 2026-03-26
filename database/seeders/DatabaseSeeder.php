<?php

    namespace Database\Seeders;

    use App\Models\Booking;
    use App\Models\User;
    use App\Models\Workshop;
    use App\Models\WorkshopSession;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Carbon;

    class DatabaseSeeder extends Seeder
    {
        public function run(): void
        {
            $this->call(RoleSeeder::class);

            $admin = User::create([
                'name'     => 'Adminisztrátor',
                'email'    => 'admin@workshop.hu',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('admin');

            $instructor = User::create([
                'name'     => 'Kovács Márta',
                'email'    => 'marta@workshop.hu',
                'password' => bcrypt('password'),
            ]);
            $instructor->assignRole('instructor');

            $support = User::create([
                'name'     => 'Nagy Péter',
                'email'    => 'peter@workshop.hu',
                'password' => bcrypt('password'),
            ]);
            $support->assignRole('support');

            $testUser = User::create([
                'name'     => 'Teszt Felhasználó',
                'email'    => 'user@workshop.hu',
                'password' => bcrypt('password'),
            ]);
            $testUser->assignRole('user');

            $extraUser = User::create([
                'name'     => 'Kiss János',
                'email'    => 'janos@workshop.hu',
                'password' => bcrypt('password'),
            ]);
            $extraUser->assignRole('user');

            $workshops = [
                [
                    'name'             => 'Túrós gombóc készítés',
                    'description'      => 'Fedezd fel a klasszikus magyar túrós gombóc titkait! Megtanulod a tökéletes tészta elkészítését, a töltelék összeállítását és a megfőzés fortélyait. A workshop végén közösen elfogyasztjuk az elkészült finomságokat.',
                    'duration_minutes' => 120,
                    'price_per_person' => 8900,
                ],
                [
                    'name'             => 'Házi lángos workshop',
                    'description'      => 'A lángos a magyar utcai konyha koronájának számít. Ebben a workshopban megtanulod a ropogós-puha lángos tészta elkészítését, a sütés technikáját és a különféle feltétek kombinálását.',
                    'duration_minutes' => 90,
                    'price_per_person' => 6500,
                ],
                [
                    'name'             => 'Kürtőskalács mesterkurzus',
                    'description'      => 'Készítsd el a világ legjobb kürtőskalácsát saját kezűleg! A workshop során megismered az élesztős tészta titkait, a nyújtás és csavarás technikáját, valamint a tökéletes karamellizálás módszerét.',
                    'duration_minutes' => 150,
                    'price_per_person' => 11500,
                ],
            ];

            foreach ($workshops as $workshopData) {
                $workshop = Workshop::create([
                    ...$workshopData,
                    'user_id'  => $instructor->id,
                    'archived' => false,
                ]);

                $sessionDates = [
                    Carbon::now()->addDays(7)->setHour(10)->setMinute(0),
                    Carbon::now()->addDays(14)->setHour(14)->setMinute(0),
                    Carbon::now()->addDays(28)->setHour(10)->setMinute(0),
                    Carbon::now()->addDays(45)->setHour(16)->setMinute(0),
                ];

                foreach ($sessionDates as $date) {
                    $session = WorkshopSession::create([
                        'workshop_id'  => $workshop->id,
                        'starts_at'    => $date,
                        'max_capacity' => 10,
                    ]);

                    Booking::create([
                        'user_id'     => $testUser->id,
                        'session_id'  => $session->id,
                        'headcount'   => 2,
                        'amount_paid' => $workshop->price_per_person * 2,
                    ]);

                    Booking::create([
                        'user_id'     => $extraUser->id,
                        'session_id'  => $session->id,
                        'headcount'   => 1,
                        'amount_paid' => $workshop->price_per_person,
                    ]);
                }
            }
        }
    }
