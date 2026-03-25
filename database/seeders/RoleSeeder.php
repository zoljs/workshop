<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Spatie\Permission\Models\Role;

    class RoleSeeder extends Seeder
    {
        public function run(): void
        {
            Role::firstOrCreate(['name' => 'user']);
            Role::firstOrCreate(['name' => 'instructor']);
            Role::firstOrCreate(['name' => 'support']);
            Role::firstOrCreate(['name' => 'admin']);
        }
    }
