<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use miudl\Carrera\CarreraRepositoryInterface;
use miudl\Estudiante\EstudianteRepositoryInterface;
use miudl\Estudiante\EstudianteValidatorInterface;
use miudl\Facultad\FacultadRepositoryInterface;
use miudl\Inscripcion\InscripcionRepository;
use miudl\Inscripcion\InscripcionValidator;

class InscripcionController extends Controller
{
    protected $repository;
    protected $repositoryEstudiante;
    protected $repositoryCarrera;
    protected $repositoryFacultad;
    protected $validator;
    protected $validatorEstudiante;

    public function __construct(InscripcionRepository $_repository, CarreraRepositoryInterface $_repositoryCarrera, FacultadRepositoryInterface $_repositoryFacultad, EstudianteRepositoryInterface $_repositoryEstudiante, InscripcionValidator $_validator, EstudianteValidatorInterface $_validatorEstudiante)
    {
        $this->repository = $_repository;
        $this->repositoryEstudiante = $_repositoryEstudiante;
        $this->repositoryCarrera = $_repositoryCarrera;
        $this->repositoryFacultad = $_repositoryFacultad;
        $this->validator = $_validator;
        $this->validatorEstudiante = $_validatorEstudiante;
    }

    protected $_url = '/administracion/inscripcion';


    public function index()
    {
        return view('admin.inscripcion.index');
    }

    public function create()
    {
        return view('admin.inscripcion.create');
    }

    public function register()
    {
        return view('admin.inscripcion.registrar');
    }

    public function generarFormulario()
    {
        return view('admin.inscripcion.print');
    }

    public function generarCarne($carrera_id)
    {
        $carrera = $this->repositoryCarrera->findOrFail($carrera_id);
        $anio = date("Y");
        $correlativo = $this->repositoryEstudiante->getCorrelativo();

        return $carrera->Codigo.'-'.$anio.'-'.$correlativo;
    }

    public function registrar(Request $request)
    {

        $data_estudiante = $request->except('_token','centrouniversitario','carrera');
        $data_inscripcion = $request->except('_token');
        //return $data_inscripcion;

        $respuesta = $this->validator->isValid($data_inscripcion);
        if (!isset($respuesta['error'])) {
            $inscripcion = $respuesta;
            $respuesta = [];
            try {
                $carne = $this->generarCarne($inscripcion['Carrera_id']);
                $data_estudiante['Carne'] = $carne;
                $respuesta = $this->validatorEstudiante->isValid($data_estudiante);
                if (!isset($respuesta['error'])) {
                    $estudiante = $respuesta;
                    $respuesta = [];
                    if ($model = $this->repositoryEstudiante->create($estudiante)) {
                        event(new \App\Events\EventInserted(["Actividad"=>"registro de estudiante","ItemId"=>$model->id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                        return redirect($this->_url.'/create')->with('info', 'El estudiante se ha creado de forma exitosa');
                    }
                }
                else {
                    return redirect()->back()->with('error', $respuesta['mensaje'])->withErrors($respuesta['errors'])->withInput();
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

    public function store(Request $request)
    {
        $data_global = $request->except('_token');
        $data_estudiante = $request->except('_token','centrouniversitario','carrera');
        $data_inscripcion = $request->except('_token','centrouniversitario','carrera');
        $data_inscripcion['CentroUniversitario_id'] = $data_global['centrouniversitario']['id'];
        $data_inscripcion['Carrera_id'] = $data_global['carrera']['id'];

        $respuesta = $this->validator->isValid($data_inscripcion);
        if (!isset($respuesta['error'])) {
            $inscripcion = $respuesta;
            $respuesta = [];
            try {
                $carne = $this->generarCarne($inscripcion->Carrera_id);
                $data_estudiante['Carne'] = $carne;
                $respuesta = $this->validatorEstudiante->isValid($data_estudiante);
                if (!isset($respuesta['error'])) {
                    $estudiante = $respuesta;
                    $respuesta = [];
                    \DB::beginTransaction();
                    $estudiante = $this->repositoryEstudiante->create($estudiante);
                    $inscripcion['Estudiante_id'] = $estudiante->id;
                    $inscripcion = $this->repository->create();
                    if ($model = $this->repository->create($inscripcion)) {
                        event(new \App\Events\EventInserted(["Actividad"=>"registro de facultad","ItemId"=>$model->id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                        \DB::commit();
                        return redirect($this->_url)->with('info', 'La facultad se ha creado de forma exitosa');
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

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        $respuesta = $this->validator->isValidUpdate($data, $id);
        if (!isset($respuesta['error'])) {
            $data = $respuesta;
            $respuesta = [];
            try {
                if ($model = $this->repository->update($id, $data)) {
                    event(new \App\Events\EventUpdated(["Actividad"=>"actualización de estudiante","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                    return redirect($this->_url)->with('info', 'La estudiante se ha modificado de forma exitosa');
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
                event(new \App\Events\EventDeleted(["Actividad"=>"eliminación de estudiante","ItemId"=>$id,"ItemNombre"=>$model->Nombre,"Url"=>$this->_url]));
                $msj = 'La estudiante se eliminó';
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
            else return redirect($this->_url)->with('error', 'Ocurrió un problema al tratar de eliminar el centro universtitario');
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
    public function getEstudianteAPI($id)
    {
        $data = $this->repository->findOrFail($id);
        return $data;
    }
    /*Funciones para API*/
    public function getInscripcionesAPI(Request $request)
    {
        $params = $request->all();
        $data = $this->repository->searchInscripciones($params, true);
        $serialized = \App\Utils\Serializer::serializeArray($data, new \miudl\Inscripcion\InscripcionTransformer);
        return \Response::json($serialized,200);
    }

    public function getCarrerasAPI($id)
    {
        $data = $this->repository->searchCarrerasRelated($id);
        //$serialized = \App\Utils\Serializer::serializeArray($data, new \miudl\Carrera\CarreraTransformer);
        return \Response::json(array("data"=>$data),200);
    }

    public function imprimirFormulario(Request $request)
    {
        $params = $request->all();
        $tipo_inscripcion = $params['tipo_inscripcion'];


        switch ($tipo_inscripcion) {
            case 'inscripcion': return $this->imprimirFormularioNuevo($params);
            case 'reinscripcion': return $this->imprimirBoleta($params);
        }
    }

    public function imprimirFormularioNuevo($params)
    {
        $carrera = json_decode($params['carrera'], true);
        $facultad = $this->repositoryCarrera->getFacultadRelated($carrera['id']);
        $centroUniversitario = json_decode($params['centrouniversitario'], true);
        $bars = "
            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
            <style>
                @page { margin: 0px; }
                html{
                    margin: 20px 32px;
                }
                .datos_inscripcion, .datos_estudiante{
                    border: 1px solid black;
                    width: 100%;
                    padding: 5px 15px 0px 15px;
                    margin-bottom: 25px;
                }
                .datos_estudiante{                    
                    height: 140px;                    
                }
                .datos_inscripcion{
                    height: 240px;
                }
                .datos_estudiante label{
                    margin-top: 10px;
                    border-bottom: 1px solid black;
                }
                .datos_estudiante .line{
                    display: block;                    
                    width: 100%;
                }
                .datos_estudiante .inline label{
                    display: inline-block !important;                    
                    width: 48.5% !important;
                    margin-right: 10px !important;
                }
                ".\App\Utils\Utils::getHeaderCSS()."
                
            </style>
            <body>
            ".\App\Utils\Utils::getHeader()." 
            <h3 class='title'>Formulario de inscripción</h3>
            <h4 class='title'>Campus ".$centroUniversitario['Nombre']."</h4>
            <h4 class='title'>Facultad de ".$facultad->Nombre."</h4>
            
            <div class='datos_estudiante'>
                <div class='inline'>
                    <label>Fecha: </label>                    
                </div>
                <label class='line'>Nombre: </label>
                <label class='line'>Apellidos: </label>
                <label class='line'>Fecha de nacimiento: </label>
            </div>
            <div class='datos_inscripcion'>
                lorem ipsum 
            </div>
            <label class='line'>F___________________________</label>
            ";


        $bars .= "</body>";

        ob_flush();
        flush();
        gc_collect_cycles();

        return \PDF::loadHTML($bars)->setPaper('LETTER')->setOrientation('portrait')->download('FormularioInscripcion.pdf');
    }
}
