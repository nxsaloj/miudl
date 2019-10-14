<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use miudl\Usuario\UsuarioRepositoryInterface;
use miudl\Usuario\UsuarioValidatorInterface;

class UsuarioController extends Controller
{
    protected $repository;
    protected $validator;
    public function __construct(UsuarioRepositoryInterface $_repository, UsuarioValidatorInterface $_validator)
    {
        $this->repository = $_repository;
        $this->validator = $_validator;
    }

    protected $_url = '/administracion/usuarios';

    public function login(Request $request){
        if(!\Auth::check())
        {
            $data = $request->all();
            $data['recordar'] = ($request->has('remember')) ? true : false;
            $respuesta = $this->validator->isValidLogin($data);
            if (!isset($respuesta['error'])) {
                $data = $respuesta;
                if(!\Auth::attempt(array('Usuario' => $data['Usuario'],'password' => $data['password'],'Deleted_at'=>NULL,'Deactivated_at'=>NULL), $data['Recordar']))
                {
                    return redirect()->back()->with('no_login', 'El usuario y/o la contraseña no coinciden, por favor, verifica los datos ingresados');
                }
            }
            else {
                return redirect()->back()->with('no_login', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
            }

            return redirect('/');
        }
        else
        {
            return redirect('/');
        }
    }

    public function change(Request $request){
        if(Auth::check())
        {
            $user = Auth::user();
            if($user->Changed_at == null)
            {
                $data = $request->all();
                $respuesta = $this->repository->change($user->id, $data);
                if ($respuesta['error'] == true){
                    $errors = isset($respuesta['errors'])? ($respuesta['errors']):null;
                    return redirect()->back()->with('no_login', $respuesta['mensaje'])->withErrors($errors);
                }
                return redirect('/logout');
            }
            else abort(404);
        }
        return redirect()->back();
    }

    public function recuperar(Request $request)
    {
        $data = $request->all();
        $respuesta = Usuario::recuperarPassword($data);
        if ($respuesta['error'] == true){
            $errors = isset($respuesta['errors'])? ($respuesta['errors']):null;
            return redirect()->back()->with('no_login', $respuesta['mensaje'])->withErrors($errors);
        }
        return redirect('/login')->with('no_login', $respuesta['mensaje']);
    }
    /*public function index()
    {
        return view('admin.usuarios.index');
    }

    public function create()
    {
        //$puestos = \App\Models\RRHH\PuestoTrabajo::getPuestosTrabajo(null, null);
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        if(!Input::has('session_keep'))
        {
            $data = Input::all();   //$request->all();
            $respuesta = Usuario::crearUsuario($data);
            if ($respuesta['error'] == true){

                if(isset($respuesta['exception'])) return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
                else return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
            }
            else
                return \App\Models\Shared\SessionKeep::getRedirect(redirect($this->_url)->with('info', 'El usuario se ha creado de forma exitosa'));
        }
        else
        {
            $datatemp = Input::only(['codigo','nombre','apellidos','puesto']);
            return \App\Models\Shared\SessionKeep::goToPage($this->_url.'/create',Input::get('session_keep'),'usuario',$datatemp);
        }
    }

    public function show($id)
    {
        $usuario = Usuario::getUsuario($id);
        if(!$usuario) abort(404);
        return view('admin.usuarios.show', array("usuario"=>$usuario));
    }


    public function update(Request $request, $id)
    {
        if(!Input::has('session_keep'))
        {
            $data = Input::all();   //$request->all();
            $respuesta = Usuario::editarUsuario($id, $data);
            if ($respuesta['error'] == true){
                if(isset($respuesta['exception'])) return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
                else return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
            }
            else
                return redirect($this->_url)->with('info', 'El usuario se ha modificado de forma exitosa');
        }
        else
        {
            $datatemp = Input::only(['codigo','nombre','apellidos','puesto']);
            return \App\Models\Shared\SessionKeep::goToPage($this->_url.'/'.$id.'/edit',Input::get('session_keep'),'usuario',$datatemp,$id);
        }
    }

    public function destroy(Request $request,$id)
    {
        $respuesta = Usuario::eliminarUsuario($id);
        if ($respuesta['error'] == true){
            if(!in_array('api',$request->route()->action['middleware']))
                if(isset($respuesta['exception'])) return redirect()->back()->with('error', 'Ocurrió un problema al tratar de eliminar el usuario')->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
                else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de eliminar el usuario');
            else
                return \Response::json(array(
                    "data"=>null,
                    "meta"=>array("msj"=>$respuesta['mensaje'],"exception"=> (isset($respuesta['exception'])? $respuesta['exception']:null )  )),  400);
        }
        else
        {
            $msj = 'El usuario se eliminó';
            if(!in_array('api',$request->route()->action['middleware']))
                return redirect($this->_url)->with('info', $msj);
            else
            {
                if(Input::has('flash')) \Session::put('info', $msj);
                return \Response::json(array(
                    "data"=>array('Url'=>$this->_url),
                    "meta"=>array("msj"=>$msj)), 200);
            }
        }
    }

    public function desactivar(Request $request,$id)
    {
        $respuesta = Usuario::desactivarUsuario($id);
        if ($respuesta['error'] == true){
            if(!in_array('api',$request->route()->action['middleware']))
                if(isset($respuesta['exception'])) return redirect()->back()->with('error', 'Ocurrió un problema al tratar de desactivar el usuario')->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
                else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de desactivar el usuario');
            else
                return \Response::json(array(
                    "data"=>null,
                    "meta"=>array("msj"=>$respuesta['mensaje'],"exception"=> (isset($respuesta['exception'])? $respuesta['exception']:null )  )),  400);
        }
        else
        {
            $msj = 'El usuario se desactivó';
            if(!in_array('api',$request->route()->action['middleware']))
                return redirect($this->_url)->with('info', $msj);
            else
            {
                if(Input::has('flash')) \Session::put('info', $msj);
                return \Response::json(array(
                    "data"=>array('Url'=>$this->_url),
                    "meta"=>array("msj"=>$msj)), 200);
            }
        }
    }

    public function reactivar(Request $request,$id)
    {
        $respuesta = Usuario::reactivarUsuario($id);
        if ($respuesta['error'] == true){
            if(!in_array('api',$request->route()->action['middleware']))
                if(isset($respuesta['exception'])) return redirect()->back()->with('error', 'Ocurrió un problema al tratar de desactivar el usuario')->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
                else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de desactivar el usuario');
            else
                return \Response::json(array(
                    "data"=>null,
                    "meta"=>array("msj"=>$respuesta['mensaje'],"exception"=> (isset($respuesta['exception'])? $respuesta['exception']:null )  )),  400);
        }
        else
        {
            $msj = 'El usuario se reactivó';
            if(!in_array('api',$request->route()->action['middleware']))
                return redirect($this->_url)->with('info', $msj);
            else
            {
                if(Input::has('flash')) \Session::put('info', $msj);
                return \Response::json(array(
                    "data"=>array('Url'=>$this->_url),
                    "meta"=>array("msj"=>$msj)), 200);
            }
        }
    }

    public function reasignar(Request $request,$id)
    {
        $respuesta = Usuario::reasignarUsuario($id);
        if ($respuesta['error'] == true){
            if(!in_array('api',$request->route()->action['middleware']))
                if(isset($respuesta['exception'])) return redirect()->back()->with('error', 'Ocurrió un problema al tratar de reasignar la contraseña del usuario')->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
                else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de reasignar la contraseña del usuario');
            else
                return \Response::json(array(
                    "data"=>null,
                    "meta"=>array("msj"=>$respuesta['mensaje'],"exception"=> (isset($respuesta['exception'])? $respuesta['exception']:null )  )),  400);
        }
        else
        {
            $msj = 'La contraseña se reasignó';
            if(!in_array('api',$request->route()->action['middleware']))
                return redirect($this->_url)->with('info', $msj);
            else
            {
                if(Input::has('flash')) \Session::put('info', $msj);
                return \Response::json(array(
                    "data"=>array('Url'=>$this->_url),
                    "meta"=>array("msj"=>$msj)), 200);
            }
        }
    }*/

    /*Otras funciones*/
    public function getUserName($nombre,$apellidos, $estudiante=false)
    {
        $fname = explode(" ", $nombre)[0];
        $fsname = ($apellidos)? explode(" ", $apellidos)[0]:null;
        $fsname = ($fsname)? $apellidos[0]:"";
        $post = ($estudiante)? "ci":"edm";

        $user = mb_strtolower($fname.$fsname."@".$post);

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
