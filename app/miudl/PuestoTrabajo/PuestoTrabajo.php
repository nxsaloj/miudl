<?php

namespace miudl\PuestoTrabajo;
use miudl\Base\BaseModel;

class PuestoTrabajo extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_PuestoTrabajo';
	public $primaryKey = 'id';
    protected $fillable = ['id','Codigo','Nombre','Deleted_at'];
    public $timestamps = true;

}
