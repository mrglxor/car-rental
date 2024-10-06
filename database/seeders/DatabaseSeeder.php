<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@account.dev',
                'address' => '123 Admin St',
                'phone_number' => '081234567890',
                'sim_number' => 'SIM123456',
                'password' => bcrypt('@Password1'),
                'role' => 'admin',
                'profile_picture' => 'user.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@account.dev',
                'address' => '456 Staff St',
                'phone_number' => '081234567891',
                'sim_number' => 'SIM123457',
                'password' => bcrypt('@Password1'),
                'role' => 'staff',
                'profile_picture' => 'user.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@account.dev',
                'address' => '789 Customer St',
                'phone_number' => '081234567892',
                'sim_number' => 'SIM123458',
                'password' => bcrypt('@Password1'),
                'role' => 'customer',
                'profile_picture' => 'user.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('cars')->insert([
            [
                'plate_number' => 'B1234XYZ',
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'daily_rental_rate' => 500000,
                'status' => 'available',
                'year' => 2020,
                'color' => 'Silver',
                'mileage' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
                'owner_id' => 1
            ],
            [
                'plate_number' => 'B5678ABC',
                'brand' => 'Honda',
                'model' => 'Civic',
                'daily_rental_rate' => 750000,
                'status' => 'not_available',
                'year' => 2021,
                'color' => 'Black',
                'mileage' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
                'owner_id' => 3
            ],
            [
                'plate_number' => 'B9101DEF',
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'daily_rental_rate' => 450000,
                'status' => 'rented',
                'year' => 2019,
                'color' => 'White',
                'mileage' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
                'owner_id' => 1
            ],
            [
                'plate_number' => 'B3141JKL',
                'brand' => 'BMW',
                'model' => 'Series 3',
                'daily_rental_rate' => 1500000,
                'status' => 'maintenance',
                'year' => 2023,
                'color' => 'Blue',
                'mileage' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
                'owner_id' => 3
            ],
        ]);

        DB::table('rentals')->insert([
            [
                'user_id' => 3,
                'car_id' => 3,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(3),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'car_id' => 1,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now(),
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('returns')->insert([
            [
                'rental_id' => 2,
                'car_id' => 1,
                'condition' => 'good',
                'returned_at' => now(),
                'total_fee' => 1500000.00,
                'comments' => 'Mobil dalam kondisi baik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
