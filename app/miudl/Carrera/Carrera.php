<?php

namespace miudl\Carrera;
use miudl\Base\BaseModel;

class Carrera extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Carrera';
	public $primaryKey = 'id';
    protected $fillable = ['id','Codigo','Nombre','Ciclos','Facultad_id','Deleted_at'];
    public $timestamps = true;

}
