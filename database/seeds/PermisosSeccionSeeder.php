<?php

use Illuminate\Database\Seeder;

class PermisosSeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = array(
            array('SeccionApp_id'=>'seccion_rrhh',
                'PuestoTrabajo_id' => [1,2]
            ),
            array('SeccionApp_id'=>'seccion_administracion',
                'PuestoTrabajo_id' => [1,5,6]
            ),
            array('SeccionApp_id'=>'seccion_finanzas',
                'PuestoTrabajo_id' => [1,3,4]
            ),
            array('SeccionApp_id'=>'seccion_educacion',
                'PuestoTrabajo_id' => [1,5,6]
            ),
            //FIN DE PADRES
            //RRHH
            array('SeccionApp_id'=>'seccion_usuarios',
                'PuestoTrabajo_id' => [1,2,6]
            ),
            array('SeccionApp_id'=>'seccion_permisos',
                'PuestoTrabajo_id' => [1]
            ),
            array('SeccionApp_id'=>'seccion_logs',
                'PuestoTrabajo_id' => [1,2,6]
            ),
            array('SeccionApp_id'=>'gestiones',
                'PuestoTrabajo_id' => [1,5]
            ),
            array('SeccionApp_id'=>'inscripcion',
                'PuestoTrabajo_id' => [1,5]
            ),
            //FINANZAS
            array('SeccionApp_id'=>'matriculacion',
                'PuestoTrabajo_id' => [1,5]
            ),
            array('SeccionApp_id'=>'seccion_puestos_trabajo',
                'PuestoTrabajo_id' => [1,2]
            ),
            array('SeccionApp_id'=>'seccion_trabajadores',
                'PuestoTrabajo_id' => [1,2]
            ),
            array('SeccionApp_id'=>'seccion_configuracion_pagos',
                'PuestoTrabajo_id' => [1,3,4]
            ),
            array('SeccionApp_id'=>'registrar_pagos',
                'PuestoTrabajo_id' => [1,4]
            ),
            array('SeccionApp_id'=>'historial_pagos',
                'PuestoTrabajo_id' => [1,3,4]
            ),
            array('SeccionApp_id'=>'seccion_centros_universitarios',
                'PuestoTrabajo_id' => [1,5]
            ),
            array('SeccionApp_id'=>'seccion_facultades',
                'PuestoTrabajo_id' => [1,5]
            ),
            array('SeccionApp_id'=>'seccion_carreras',
                'PuestoTrabajo_id' => [1,5]
            ),
            array('SeccionApp_id'=>'seccion_cursos',
                'PuestoTrabajo_id' => [1,5]
            ),
            array('SeccionApp_id'=>'seccion_estudiantes',
                'PuestoTrabajo_id' => [1,5]
            ),
        );

        $permisos = [];
        foreach($models as $model)
        {
            //\Log::debug("PI".json_encode($model));
            foreach($model['PuestoTrabajo_id'] as $puesto)
            {
                $array = array('SeccionApp_id'=>$model['SeccionApp_id'],
                    'PuestoTrabajo_id' => $puesto
                );
                array_push($permisos,$array);
            }
        }
    }
}
