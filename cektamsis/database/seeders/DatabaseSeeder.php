<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Role Admin
            User::create([
            'name' => 'Zilch',
            'email' => 'zilch@zilch.com',
            'role' => UserRole::ADMIN,
            'password' => Hash::make('GodIsOne'),
        ]);

        //Role Admin
            User::create([
            'name' => 'Ragil',
            'email' => 'ragil@gmail.com',
            'role' => UserRole::ADMIN,
            'password' => Hash::make('1234578'),
        ]);

        // Role Guru
        User::create([
            'name' => 'Ki Ali',
            'email' => 'kiali@gmail.com',
            'role' => UserRole::GURU,
            'password' => Hash::make('1234578'),
        ]);
        User::create([
            'name' => 'Ki Juli',
            'email' => 'kijuli@gmail.com',
            'role' => UserRole::GURU,
            'password' => Hash::make('1234578'),
        ]);
        User::create([
            'name' => 'Ki Jalu',
            'email' => 'kijalu@gmail.com',
            'role' => UserRole::GURU,
            'password' => Hash::make('1234578'),
        ]);
        User::create([
            'name' => 'Ki Dzul',
            'email' => 'kidzul@gmail.com',
            'role' => UserRole::GURU,
            'password' => Hash::make('1234578'),
        ]);

        // Role Siswa atau User
        User::create([
            'name' => 'tubagus',
            'email' => 'agus@gmail.com',
            'role' => UserRole::USER,
            'password' => Hash::make('1234578'),
        ]);
        User::create([
            'name' => 'Ryan',
            'email' => 'ryan@gmail.com',
            'role' => UserRole::USER,
            'password' => Hash::make('1234578'),
        ]);
        User::create([
            'name' => 'Kenzo',
            'email' => 'kenzo@gmail.com',
            'role' => UserRole::USER,
            'password' => Hash::make('1234578'),
        ]);
        User::create([
            'name' => 'brury',
            'email' => 'brury@gmail.com',
            'role' => UserRole::USER,
            'password' => Hash::make('1234578'),
        ]);

       $this->call([
            JurusanSeeder::class,
            MapelSeeder::class,
            GuruSeeder::class,   // guru harus ada dulu
            KelasSeeder::class,  // baru kelas
            SiswaSeeder::class,
        ]);
    }
}
