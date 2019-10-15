<?php

namespace miudl\Facultad;
use miudl\Base\BaseModel;

class Facultad extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Facultad';
	public $primaryKey = 'id';
    protected $fillable = ['id','Codigo','Nombre','Deleted_at'];
    public $timestamps = true;

}
