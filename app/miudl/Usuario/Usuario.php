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

    public static function getDefault()
    {
        return "miudl_2019";
    }
    public static function getUserName($nombre,$apellidos, $estudiante=false)
    {
        $fname = ($nombre)? explode(" ", $nombre)[0]:null;
        $fname = ($fname)? $fname[0]:"";
        $fsname = ($apellidos)? explode(" ", $apellidos):null;
        $fsname1 = (sizeof($fsname)>0)? $fsname[0]:"";
        $fsname2 = (sizeof($fsname)>1)? $fsname[1][0]:"";
        $post = "miudl";

        $user = mb_strtolower($fname.$fsname1.$fsname2."@".$post);

        $extra = 0;
        $exists = self::where('Usuario',$user)->first();
        while($exists)
        {
            $user =  mb_strtolower($fname.$fsname.($extra? $extra:"")."@".$post);
            $exists = self::where('Usuario',$user)->first();
            $extra += 1;
        }
        return $user;
    }
}
