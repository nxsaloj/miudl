<?php

namespace miudl\Curso;
use miudl\Base\BaseModel;

class Curso extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Curso';
	public $primaryKey = 'id';
    protected $fillable = ['id','Codigo','Nombre','Deleted_at'];
    public $timestamps = true;

    public function Carreras(){
        return $this->belongsToMany('miudl\Carrera\Carrera','TB_CursosCarrera','Curso_id','Carrera_id');
    }
}
