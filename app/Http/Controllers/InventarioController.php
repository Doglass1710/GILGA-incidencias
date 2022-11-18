<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Incidencia;
use Carbon\Carbon;
use Image;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function incidencias_sistemas(Request $request)
    {
        $user = \Auth::user();

        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat(ue.estacion," - ",e.nombre_corto) sucursal,ue.id_usuario_permiso'))
        ->join('estaciones as e','ue.estacion','=','e.estacion')
        ->where('ue.id_usuario_permiso', '=', $user->id)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(ue.estacion as unsigned)')
        ->get();

        $area = DB::table('inventario_areas')
        ->select(DB::raw('id,descripcion'))
        ->get();

        if($request->input("area")==null)
        {
            return view('inventario.captura_incidencia_sistemas',["estaciones"=>$estaciones, "area" => $area]);
        }
        else
        {
            //LA POSICION 0 SE REFIERE A EQUIPOS DE INVENTARIO
            $posicion=0;
            $cantidad = " ";
            //$id_refaccion=178;

            $incidencia = new Incidencia;
            $incidencia->id_usuario =$user->id;
            $incidencia->folio = $request->input('folio');
            $incidencia->estacion = $request->input('estacion');
            $incidencia->tipo_solicitud = 'incidencia';
            $incidencia->fecha_incidencia = Carbon::now();
            $incidencia->id_area_atencion = 1;
            $incidencia->estatus_incidencia = 'ABIERTA';

            $incidencia->asunto = $request->input('folio_equipo');
            $incidencia->descripcion = $request->input('descripcion');
            $incidencia->prioridad = 'alta';
            $incidencia->id_area_estacion = $request->input('area');
            $incidencia->id_refaccion = $request->input('subarea');
            $incidencia->id_equipo = $request->input('equipos');
            $incidencia->posicion = $posicion;
            $incidencia->cantidad = $cantidad; 

            $image_path = $request->file('foto_ruta');
            if ($image_path) {        
                $image_path_name = time().$image_path->getClientOriginalName();     
                $ruta= storage_path('app/incidencias/'.$image_path_name);   
                Image::make($image_path->getRealPath())->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($ruta,60);
                $incidencia->foto_ruta = $image_path_name;
            }
            $incidencia->save();

            return Redirect::to('listado_incidencias')->with(['message' => 'Incidencia Generada Correctamente']);
        }
                
    }

    public function GetSubareas(Request $request){
        if ($request->ajax()){
            $subareas = DB::table('inventario_subareas')
            ->select(DB::raw('id,descripcion'))
            ->where('id_area','=',$request->area)
            ->get();
            return response()->json($subareas);
        }
    }

    public function tabla_equipos(Request $request)
    {
        if ($request->ajax()){
            $equipos = DB::table('inventario_equipos')
            ->select(DB::raw('id, descripcion, id_area'))
            ->where('id_area','=',$request->area)
            ->where('estatus','=',1)
            ->get();  
        }
        return response()->json($equipos);
    }

    public function folio_equipos(Request $request)
    {        
        $folio = DB::table('inventario')
                ->select(DB::raw('folio,marca,modelo,serie'))
                ->where('estacion','=',$request->estacion)
                ->where('subarea','=',$request->subarea)
                ->where('equipo','=',$request->equipo)
                ->get();
        
        return response($folio);
        //return response()->json($folio);
    }

}
