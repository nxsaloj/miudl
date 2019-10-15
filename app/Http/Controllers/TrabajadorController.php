<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use miudl\Trabajador\TrabajadorRepositoryInterface;
use miudl\Trabajador\TrabajadorValidatorInterface;

class TrabajadorController extends Controller
{
    protected $repository;
    protected $validator;
    public function __construct(TrabajadorRepositoryInterface $_repository, TrabajadorValidatorInterface $_validator)
    {
        $this->repository = $_repository;
        $this->validator = $_validator;
    }

    protected $_url = '/rrhh/trabajadores';


    public function index()
    {
        return view('rrhh.trabajadores.index');
    }

    public function create()
    {
        return view('rrhh.trabajadores.create');
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
                        event(new \App\Events\EventInserted(["Actividad"=>"registro de trabajador","ItemId"=>$model->id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                        return redirect($this->_url)->with('info', 'El trabajador se ha creado de forma exitosa');
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

    public function edit($id)
    {
        $trabajador = $this->repository->findOrFail($id);
        return view('rrhh.trabajadores.edit', array("trabajador"=>$trabajador));
    }


    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        $respuesta = $this->validator->isValidUpdate($data, $id);
        if (!isset($respuesta['error'])) {
            $data = $respuesta;
            $respuesta = [];
            try {
                if ($model = $this->repository->update($id, $data)) {
                    event(new \App\Events\EventUpdated(["Actividad"=>"actualización de trabajador","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                    return redirect($this->_url)->with('info', 'El trabajador se ha modificado de forma exitosa');
                }
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
                $respuesta['error']   = true;
                \Log::channel('error')->debug('Exception ' . $e->getMessage());
                return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors(['exception_dev'=>$respuesta['exception']])->withInput();
            }
        }
        else {
            return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
        }
    }

    public function destroy(Request $request,$id)
    {
        try {
            if ($model = $this->repository->delete($id)) {
                event(new \App\Events\EventDeleted(["Actividad"=>"eliminación de trabajador","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                $msj = 'El trabajador se eliminó';
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
            else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de eliminar el trabajador');
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
    public function getTrabajadorAPI($id)
    {
        $data = $this->repository->findOrFail($id);
        return $data;
    }
    /*Funciones para API*/
    public function getTrabajadoresAPI(Request $request)
    {
        $params = $request->all();
        $data = $this->repository->search($params, true);
        $serialized = \App\Utils\Serializer::serializeArray($data, new \miudl\Trabajador\TrabajadorTransformer);
        return \Response::json($serialized,200);
    }
}
