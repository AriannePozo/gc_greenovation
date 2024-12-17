<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Inserta tipos de usuario
        DB::table('user_types')->insert([
            ['id' => 1, 'name' => 'Administrador'],
            ['id' => 2, 'name' => 'Empleado'],
        ]);

        // Inserta estados de usuario
        DB::table('user_statuses')->insert([
            ['id' => 1, 'name' => 'Activo'],
            ['id' => 2, 'name' => 'Inactivo'],
        ]);

        // Inserta usuario administrador
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('1234'),
            'user_type_id' => 1,
            'user_status_id' => 1,
            'ci' => '00000000',
        ]);
    }
}
