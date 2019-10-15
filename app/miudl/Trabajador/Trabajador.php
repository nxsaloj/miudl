<?php

namespace miudl\Trabajador;
use miudl\Base\BaseModel;

class Trabajador extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Trabajador';
	public $primaryKey = 'id';
    protected $fillable = ['id','Codigo','Nombre','Apellidos','FechaNacimiento','Usuario_id','PuestoTrabajo_id','Deleted_at'];
    public $timestamps = true;

}
