<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SeccionesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PuestoTrabajoSeeder::class);
        $this->call(PermisosSeccionSeeder::class);
    }
}
