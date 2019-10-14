<?php

namespace miudl\Logging;

use miudl\Base\BaseModel;
use miudl\Utils\Utils;
use miudl\Usuario\Usuario;

class LogObject extends BaseModel
{
    protected $table = 'TB_HistorialActividad';
    protected $primaryKey = 'id';
    protected $fillable = ['Usuario_id','Fecha','Actividad','Tipo','ItemId','ItemNombre','Url'];
    public $timestamps = true;

    public function Usuario(){
        return $this->belongsTo('miudl\Usuario\Usuario','Usuario_id','Usuario_id');
    }

    public static function getTipo($data)
    {
        $tipo = 'Creación';
        if($data->Tipo == 'event.item.inserted') $tipo = "Creación";
        else if($data->Tipo == 'event.item.updated') $tipo = "Actualización";
        else if($data->Tipo == 'event.item.deleted') $tipo = "Eliminación";

        return $tipo;
    }
}
