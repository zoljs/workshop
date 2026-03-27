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
                'email'    => 'admin@w.hu',
                'password' => bcrypt('a'),
            ]);
            $admin->assignRole('admin');

            $instructor = User::create([
                'name'     => 'Óraadó',
                'email'    => 'ora@w.hu',
                'password' => bcrypt('a'),
            ]);
            $instructor->assignRole('instructor');

            $testUser = User::create([
                'name'     => 'Beka Kálmán',
                'email'    => 'a@w.hu',
                'password' => bcrypt('a'),
            ]);
            $testUser->assignRole('user');


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
                }
            }
        }
    }
