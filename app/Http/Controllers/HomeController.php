<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidencias = DB::table('incidencias')
                ->select(DB::raw('incidencias.estacion,estaciones.nombre_corto,count(*) as total'))
                ->join('estaciones','incidencias.estacion','=','estaciones.estacion')
                ->where('incidencias.estatus_incidencia','=','ABIERTA')
                ->groupBy('incidencias.estacion')
                ->groupBy('estaciones.nombre_corto')
                ->orderByRaw('cast(incidencias.estacion as unsigned)')
                ->get();
        
        $areas_estacion = DB::table('incidencias')
                ->select(DB::raw('areas_estacion.descripcion,count(*) as total'))
                ->join('areas_estacion', function($join){
                    $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                    $join->on('incidencias.estacion','=','areas_estacion.estacion');
                })                
                ->where('incidencias.estatus_incidencia','=','ABIERTA')
                ->groupBy('areas_estacion.descripcion')
                ->get();
        
        return view('home',["incidencias"=>$incidencias,"areas_estacion"=>$areas_estacion]);
    }
}
