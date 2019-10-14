<?php

namespace miudl\Usuario;
use miudl\Base\BaseModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;

class Usuario extends Authenticable
{
    use Notifiable;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['Deleted_at'];

    protected $table = 'TB_Usuario';
    protected $primaryKey = 'id';
    protected $fillable = ['Usuario', 'password','Deactivated_at','Changed_at','Deleted_at'];
    protected $hidden = [ 'password', 'remember_token' ];
    public $timestamps = true;

    public function username()
    {
        return 'Usuario';
    }
}
