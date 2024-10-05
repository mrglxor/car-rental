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
                'mileage' => 15000, // Menambahkan mileage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_number' => 'B5678ABC',
                'brand' => 'Honda',
                'model' => 'Civic',
                'daily_rental_rate' => 750000,
                'status' => 'available',
                'year' => 2021,
                'color' => 'Black',
                'mileage' => 12000, // Menambahkan mileage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_number' => 'B9101DEF',
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'daily_rental_rate' => 450000,
                'status' => 'available',
                'year' => 2019,
                'color' => 'White',
                'mileage' => 20000, // Menambahkan mileage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_number' => 'B1121GHI',
                'brand' => 'Nissan',
                'model' => 'X-Trail',
                'daily_rental_rate' => 1000000,
                'status' => 'available',
                'year' => 2022,
                'color' => 'Red',
                'mileage' => 8000, // Menambahkan mileage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_number' => 'B3141JKL',
                'brand' => 'BMW',
                'model' => 'Series 3',
                'daily_rental_rate' => 1500000,
                'status' => 'available',
                'year' => 2023,
                'color' => 'Blue',
                'mileage' => 5000, // Menambahkan mileage
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Seeder untuk rentals
        DB::table('rentals')->insert([
            [
                'user_id' => 3,
                'car_id' => 1,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(3),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'car_id' => 2,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(7),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('returns')->insert([
            [
                'rental_id' => 1, // ID rental pertama
                'car_id' => 1,    // ID untuk Toyota Avanza
                'condition' => 'good',
                'returned_at' => now()->addDays(3), // Menggunakan tanggal kembalinya
                'total_fee' => 1500000.00, // Contoh total fee
                'comments' => 'Mobil dalam kondisi baik.', // Menambahkan komentar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rental_id' => 2, // ID rental kedua
                'car_id' => 2,    // ID untuk Honda Civic
                'condition' => 'damaged',
                'returned_at' => now()->addDays(7), // Menggunakan tanggal kembalinya
                'total_fee' => 2000000.00, // Contoh total fee
                'comments' => 'Mobil mengalami kerusakan di bagian bumper.', // Menambahkan komentar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rental_id' => 1, // Menggunakan rental_id yang sama dengan yang di atas
                'car_id' => 1,    // ID untuk Toyota Avanza
                'condition' => 'needs_maintenance',
                'returned_at' => now()->addDays(10), // Menggunakan tanggal kembalinya
                'total_fee' => 3000000.00, // Contoh total fee
                'comments' => 'Perlu perawatan rutin setelah penyewaan.', // Menambahkan komentar
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('payments')->insert([
            [
                'rental_id' => 1,
                'amount' => 1500000,
                'payment_method' => 'credit_card', // Menambahkan metode pembayaran
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rental_id' => 2,
                'amount' => 3000000,
                'payment_method' => 'bank_transfer', // Menambahkan metode pembayaran
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('reviews')->insert([
            [
                'user_id' => 3,
                'car_id' => 1,
                'rating' => 4,
                'comment' => 'Mobilnya bagus dan nyaman.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'car_id' => 2,
                'rating' => 5,
                'comment' => 'Pelayanan sangat baik!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'car_id' => 3,
                'rating' => 3,
                'comment' => 'Cukup memuaskan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'car_id' => 4,
                'rating' => 4,
                'comment' => 'Sangat suka mobilnya!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'car_id' => 5,
                'rating' => 5,
                'comment' => 'Mobil premium dengan performa yang luar biasa!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
