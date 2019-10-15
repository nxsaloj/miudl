<?php

namespace miudl\CentroUniversitario;
use miudl\Base\BaseModel;

class CentroUniversitario extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_CentroUniversitario';
	public $primaryKey = 'id';
    protected $fillable = ['id','Codigo','Nombre','Direccion','Deleted_at'];
    public $timestamps = true;

}
