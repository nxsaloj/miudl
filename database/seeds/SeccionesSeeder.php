<?php

use Illuminate\Database\Seeder;

class SeccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('TB_Seccion')->truncate();

        $models = array(
            array('id'=>'seccion_administracion',
                'Nombre' => 'Administración',
                'Url' => '/administracion',
                'Prioridad' => 1,
                'Icono' => null,
                'idPadre' => null
            ),
            array('id'=>'seccion_rrhh',
                'Nombre' => 'Recursos Humanos',
                'Url' => '/rrhh',
                'Prioridad' => 0,
                'Icono' => null,
                'idPadre' => null
            ),
            array('id'=>'seccion_finanzas',
                'Nombre' => 'Finanzas',
                'Url' => '/finanzas',
                'Prioridad' => 2,
                'Icono' => null,
                'idPadre' => null
            ),
            array('id'=>'seccion_educacion',
                'Nombre' => 'Educación',
                'Url' => '/educacion',
                'Prioridad' => 3,
                'Icono' => null,
                'idPadre' => null
            ),

            //FIN DE PADRES
            //Public
            /*array('id'=>'seccion_backup',
                'Nombre' => 'Copia de seguridad',
                'Icono' => 'mdi mdi-database',
                'Url' => 'backup',
                'idPadre' => 'seccion_administracion',
                'Prioridad' => null
            ),*/
            array('id'=>'seccion_usuarios',
                'Nombre' => 'Usuarios',
                'Icono' => 'mdi mdi-account-group',
                'Url' => 'usuarios',
                'idPadre' => 'seccion_administracion',
                'Prioridad' => 1
            ),
            array('id'=>'seccion_permisos',
                'Nombre' => 'Permisos',
                'Icono' => 'mdi mdi-account-key',
                'Url' => 'permisos',
                'idPadre' => 'seccion_administracion',
                'Prioridad' => 2
            ),
            array('id'=>'seccion_logs',
                'Nombre' => 'Registro de actividad',
                'Icono' => 'mdi mdi-calendar-text',
                'Url' => 'logs',
                'idPadre' => 'seccion_administracion',
                'Prioridad' => 3
            ),
            array('id'=>'gestiones',
                'Nombre' => 'Gestiones',
                'Icono' => 'mdi mdi-check',
                'Url' => '',
                'idPadre' => 'seccion_administracion',
                'Prioridad' => 4
            ),
            array('id'=>'inscripcion',
                'Nombre' => 'Inscripción',
                'Icono' => 'mdi mdi-library',
                'Url' => 'inscripcion',
                'idPadre' => 'gestiones',
                'Prioridad' => 1
            ),
            array('id'=>'matriculacion',
                'Nombre' => 'Matriculación',
                'Icono' => 'mdi mdi-library',
                'Url' => 'matriculacion',
                'idPadre' => 'gestiones',
                'Prioridad' => 2
            ),
            //RRHH
            array('id'=>'seccion_puestos_trabajo',
                'Nombre' => 'Puestos de trabajo',
                'Icono' => 'mdi mdi-clipboard-account',
                'Url' => 'puestos',
                'idPadre' => 'seccion_rrhh',
                'Prioridad' => 2
            ),
            array('id'=>'seccion_trabajadores',
                'Nombre' => 'Trabajadores',
                'Icono' => 'mdi mdi-account-multiple',
                'Url' => 'trabajadores',
                'idPadre' => 'seccion_rrhh',
                'Prioridad' => 3
            ),
            //FINANZAS
            array('id'=>'seccion_configuracion_pagos',
                'Nombre' => 'Configuración de pagos',
                'Icono' => 'mdi mdi-file-document-box',
                'Url' => 'cuentas',
                'idPadre' => 'seccion_finanzas',
                'Prioridad' => 1
            ),
            array('id'=>'registrar_pagos',
                'Nombre' => 'Registrar pagos',
                'Icono' => 'mdi mdi-view-dashboard-variant',
                'Url' => 'registrar_pagos',
                'idPadre' => 'seccion_gestion_pagos',
                'Prioridad' => 1
            ),
            array('id'=>'historial_pagos',
                'Nombre' => 'Historial de pagos',
                'Icono' => 'mdi mdi-view-dashboard-variant',
                'Url' => 'historial_pagos',
                'idPadre' => 'seccion_gestion_pagos',
                'Prioridad' => 2
            ),
            //Educación
            array('id'=>'seccion_centros_universitarios',
                'Nombre' => 'Centros universitarios',
                'Icono' => 'mdi mdi-rotate-right-variant',
                'Url' => 'centros_universitarios',
                'idPadre' => 'seccion_educacion',
                'Prioridad' => 0
            ),
            array('id'=>'seccion_facultades',
                'Nombre' => 'Facultades',
                'Icono' => 'mdi mdi-rotate-right-variant',
                'Url' => 'facultades',
                'idPadre' => 'seccion_educacion',
                'Prioridad' => 1
            ),
            array('id'=>'seccion_carreras',
                'Nombre' => 'Carreras',
                'Icono' => 'mdi mdi-rotate-right-variant',
                'Url' => 'carreras',
                'idPadre' => 'seccion_educacion',
                'Prioridad' => 2
            ),
            array('id'=>'seccion_cursos',
                'Nombre' => 'Cursos',
                'Icono' => 'mdi mdi-sale',
                'Url' => 'cursos',
                'idPadre' => 'seccion_educacion',
                'Prioridad' => 3
            ),
            array('id'=>'seccion_estudiantes',
                'Nombre' => 'Estudiantes',
                'Icono' => 'mdi mdi-library',
                'Url' => 'estudiantes',
                'idPadre' => 'seccion_educacion',
                'Prioridad' => 4
            )
        );


        DB::table('TB_Seccion')->insert($models);
        Schema::enableForeignKeyConstraints();
    }
}
