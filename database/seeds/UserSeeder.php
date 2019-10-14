<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('TB_Usuario')->truncate();

        $models = array(
            array(
                'Usuario' => 'admin',
                'password' => \Hash::make('MiUDL_123')
            )
        );


        DB::table('TB_Usuario')->insert($models);
        Schema::enableForeignKeyConstraints();
    }
}
