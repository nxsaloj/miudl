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
        if(\Auth::check())
        {
            $user = \Auth::user();
            if($user->Changed_at == null)
            {
                $data = $request->all();
                $respuesta = $this->validator->isValidChange($data);
                if (!isset($respuesta['error'])) {
                    $data = $respuesta;
                    $respuesta = $this->repository->change($user->id, $data);
                    if ($respuesta['error'] == true){
                        $errors = isset($respuesta['errors'])? ($respuesta['errors']):null;
                        return redirect()->back()->with('no_login', $respuesta['mensaje'])->withErrors($errors);
                    }
                }
                else {
                    return redirect()->back()->with('no_login', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
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

    public function index()
    {
        return view('admin.usuarios.index');
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $respuesta = $this->validator->isValid($data);
        if (!isset($respuesta['error'])) {
            $data = $respuesta;
            $respuesta = [];
            try {
                if ($this->repository->isDeleted($data)) $this->repository->reactivate($data);
                else {
                    if ($model = $this->repository->create($data)) {
                        event(new \App\Events\EventInserted(["Actividad"=>"registro de usuario","ItemId"=>$model->id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                        return redirect($this->_url)->with('info', 'El usuario se ha creado de forma exitosa');
                    }
                }
            }
            catch(\Illuminate\Database\QueryException $e){
                $respuesta['exception'] = $e->getMessage();
                $respuesta['mensaje'] = 'Ocurrió una excepción en la operación hacia la base de datos';
                $respuesta['error']   = true;
                \Log::channel('error')->debug('QueryException ' . $e->getMessage());
            }
            catch (\Exception $e) {
                $respuesta['exception'] = $e->getMessage();
                $respuesta['mensaje'] = 'Ocurrió una excepción en la operación';
                $respuesta['error']   = true;
                \Log::channel('error')->debug('Exception ' . $e->getMessage());
            }
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
        }
        else {
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
        }
    }

    public function show($id)
    {
        $usuario = $this->repository->findOrFail($id);
        return view('admin.usuarios.show', array("usuario"=>$usuario));
    }

    public function destroy(Request $request,$id)
    {
        try {
            if ($model = $this->repository->delete($id)) {
                event(new \App\Events\EventDeleted(["Actividad"=>"eliminación de usuario","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                $msj = 'El usuario se eliminó';
                if(!in_array('api',$request->route()->action['middleware']))
                    return redirect($this->_url)->with('info', $msj);
                else
                {
                    if($request->has('flash')) \Session::put('info', $msj);
                    return \Response::json(array(
                        "data"=>array('Url'=>$this->_url),
                        "meta"=>array("msj"=>$msj)), 200);
                }
            }
            else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de eliminar el usuario');
        }
        catch(\Illuminate\Database\QueryException $e){
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación hacia la base de datos';
            $respuesta['error']   = true;
            \Log::channel('error')->debug('QueryException ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
        }
        catch (\Exception $e) {
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación';
            $respuesta['error'] = true;
            \Log::channel('error')->debug('Exception ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev' => $respuesta['exception']])->withInput();
        }
    }

    public function desactivar(Request $request,$id)
    {
        try {
            if ($model = $this->repository->deactivate($id)) {
                event(new \App\Events\EventUpdated(["Actividad"=>"desactivación de usuario","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                $msj = 'El usuario se desactivó';
                if(!in_array('api',$request->route()->action['middleware']))
                    return redirect($this->_url)->with('info', $msj);
                else
                {
                    if($request->has('flash')) \Session::put('info', $msj);
                    return \Response::json(array(
                        "data"=>array('Url'=>$this->_url),
                        "meta"=>array("msj"=>$msj)), 200);
                }
            }
            else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de desactivar el usuario');
        }
        catch(\Illuminate\Database\QueryException $e){
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación hacia la base de datos';
            $respuesta['error']   = true;
            \Log::channel('error')->debug('QueryException ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
        }
        catch (\Exception $e) {
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación';
            $respuesta['error'] = true;
            \Log::channel('error')->debug('Exception ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev' => $respuesta['exception']])->withInput();
        }
    }
    public function reactivar(Request $request,$id)
    {
        try {
            if ($model = $this->repository->reactivate($id)) {
                event(new \App\Events\EventUpdated(["Actividad"=>"reactivación de usuario","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                $msj = 'El usuario se reactivó';
                if(!in_array('api',$request->route()->action['middleware']))
                    return redirect($this->_url)->with('info', $msj);
                else
                {
                    if($request->has('flash')) \Session::put('info', $msj);
                    return \Response::json(array(
                        "data"=>array('Url'=>$this->_url),
                        "meta"=>array("msj"=>$msj)), 200);
                }
            }
            else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de desactivar el usuario');
        }
        catch(\Illuminate\Database\QueryException $e){
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación hacia la base de datos';
            $respuesta['error']   = true;
            \Log::channel('error')->debug('QueryException ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
        }
        catch (\Exception $e) {
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación';
            $respuesta['error'] = true;
            \Log::channel('error')->debug('Exception ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev' => $respuesta['exception']])->withInput();
        }
    }
    public function reasignar(Request $request,$id)
    {
        try {
            if ($model = $this->repository->reasign($id)) {
                event(new \App\Events\EventUpdated(["Actividad"=>"reasignación de constraseña de usuario","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                $msj = 'Se reasignó la contraseña del usuario';
                if(!in_array('api',$request->route()->action['middleware']))
                    return redirect($this->_url)->with('info', $msj);
                else
                {
                    if($request->has('flash')) \Session::put('info', $msj);
                    return \Response::json(array(
                        "data"=>array('Url'=>$this->_url),
                        "meta"=>array("msj"=>$msj)), 200);
                }
            }
            else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de reasignar contraseña del usuario');
        }
        catch(\Illuminate\Database\QueryException $e){
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación hacia la base de datos';
            $respuesta['error']   = true;
            \Log::channel('error')->debug('QueryException ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
        }
        catch (\Exception $e) {
            $respuesta['exception'] = $e->getMessage();
            $respuesta['mensaje'] = 'Ocurrió una excepción en la operación';
            $respuesta['error'] = true;
            \Log::channel('error')->debug('Exception ' . $e->getMessage());
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev' => $respuesta['exception']])->withInput();
        }
    }

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

    /*Funciones para API*/
    public function getUsuariosAPI(Request $request)
    {
        $params = $request->all();
        $data = $this->repository->search($params, true);
        $serialized = \App\Utils\Serializer::serializeArray($data, new \miudl\Usuario\UsuarioTransformer);
        return \Response::json($serialized,200);
    }
}
