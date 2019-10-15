<?php

use Illuminate\Database\Seeder;

class PuestoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('TB_PuestoTrabajo')->truncate();

        $models = array(
            array(
                'id'=>1,
                'Codigo' => 'PTAD-001',
                'Nombre' => 'Administrador'
            ),
            array(
                'id'=>2,
                'Codigo' => 'PTRH-001',
                'Nombre' => 'Recursos Humanos'
            ),
            array(
                'id'=>3,
                'Codigo' => 'PTCO-001',
                'Nombre' => 'Auditoría'
            ),
            array(
                'id'=>4,
                'Codigo' => 'PTCO-002',
                'Nombre' => 'Contabilidad'
            ),
            array(
                'id'=>5,
                'Codigo' => 'PTSC-001',
                'Nombre' => 'Secretaría'
            ),
            array(
                'id'=>6,
                'Codigo' => 'PTIN-001',
                'Nombre' => 'Informática'
            )
        );


        DB::table('TB_PuestoTrabajo')->insert($models);
        Schema::enableForeignKeyConstraints();
    }
}
