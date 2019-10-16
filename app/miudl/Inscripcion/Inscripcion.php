<?php

namespace miudl\Inscripcion;
use miudl\Base\BaseModel;

class Inscripcion extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Inscripcion';
	public $primaryKey = 'id';
    protected $fillable = ['id','Estudiante_id','Carrera_id','CentroUniversitario_id','Fecha'];
    public $timestamps = true;

    public function Carrera(){
        return $this->belongsTo('miudl\Carrera\Carrera','id','Carrera_id');
    }

    public function CentroUniversitario(){
        return $this->belongsTo('miudl\CentroUniversitario\CentroUniversitario','id','CentroUniversitario_id');
    }
}
