<?php

namespace miudl\Estudiante;
use miudl\Base\BaseModel;

class Estudiante extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Estudiante';
	public $primaryKey = 'id';
    protected $fillable = ['id','Carne','Nombre','Apellidos','FechaNacimiento','Deleted_at'];
    public $timestamps = true;

    public function Carreras(){
        return $this->belongsToMany('miudl\Carrera\Carrera','TB_Inscripcion','Estudiante_id','Carrera_id');
    }

    public function Cursos(){
        return $this->belongsToMany('miudl\Carrera\Carrera','TB_AsignacionCursos','Estudiante_id','Curso_id');
    }
}
