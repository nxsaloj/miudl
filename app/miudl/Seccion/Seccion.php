<?php

namespace miudl\Seccion;
use miudl\Base\BaseModel;

class Seccion extends BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Seccion';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['Nombre','Icono','Url','Prioridad','Seccion_idPadre','Deleted_at'];
    public $timestamps = true;
    public static $admins = [1];

    public function Padre(){
        return $this->belongsTo('App\Models\App\SeccionApp','idPadre','id');
    }
    public function Secciones(){
        return $this->hasMany('App\Models\App\SeccionApp','idPadre','id');
    }
}
