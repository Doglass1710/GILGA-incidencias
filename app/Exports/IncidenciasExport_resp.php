<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;


use Illuminate\Http\Response;

use Illuminate\Http\Request;
use App\Incidencia;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\IncidenciaFormRequest;

use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Input;
//use DB;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Auth;


class IncidenciasExport implements FromQuery, ShouldAutoSize, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:T1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }    
    
    public function headings(): array
    {
        return [
            'Id Incidencia',
            'Usuario',
            'Folio',
            'Estacion',
            'Nombre Corto',
            'Fecha Incidencia',
            'Area Estacion',
            'Equipo',
            'Asunto',
            'Descripcion',
            'Area Atencion',
            'Estatus',
            'Tipo Solicitud',
            'Prioridad',
            'Fecha Ultima Actualizacion',
            'Fecha Cierre',
            'Dias Vida Incidencia',
            'Posicion',
            'Producto',
            'Refaccion',
        ];
    }
    
    public function __construct($estacion,$fecha_desde,$fecha_hasta)
    {
        $this->estacion = $estacion;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
    }
    
    public function query()
    {
        //CORPORATIVO para que no me ligue id_equipo con refacciones
        $condicion_idEquipo1='incidencias.id_equipo';
        $condicion_idEquipo2='refacciones.id_equipo';       

       if ($this->estacion=='CORPORATIVO'){
        $condicion_idEquipo1='incidencias.id_refaccion';
        $condicion_idEquipo2='refacciones.id';
        }


        if($this->estacion == '*'){

            return Incidencia::query()
                ->select(DB::raw('incidencias.id,users.name,incidencias.folio,incidencias.estacion,estaciones.nombre_corto,incidencias.fecha_incidencia,areas_estacion.descripcion as Area_Estacion,equipos.descripcion as Equipo,incidencias.asunto,incidencias.descripcion,areas_atencion.descripcion as Area_Atencion,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,incidencias.posicion,incidencias.producto,refacciones.descripcion as Refaccion'))
                ->join('users','incidencias.id_usuario','=','users.id')
                ->join('estaciones','incidencias.estacion','=','estaciones.estacion')
                ->join('areas_estacion', function($join){
                    $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                    $join->on('incidencias.estacion','=','areas_estacion.estacion');
                })
                ->join('equipos', function($join){
                    $join->on('incidencias.id_equipo','=','equipos.id');
                    $join->on('incidencias.estacion','=','equipos.estacion');
                })
                ->join('areas_atencion','incidencias.id_area_atencion','=','areas_atencion.id')
                ->join('refacciones', function($join){
                    $join->on('incidencias.id_refaccion','=','refacciones.id');
                    $join->on('incidencias.estacion','=','refacciones.estacion');
                    $join->on('incidencias.id_equipo','=','refacciones.id_equipo');
                })                     
                ->where ('incidencias.estacion','<>','CORPORATIVO') 
                ->whereRaw("cast(incidencias.fecha_incidencia as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")               
                ->orderBy('incidencias.estacion')
                ->orderBy('incidencias.estatus_incidencia') 
                ->orderBy('incidencias.fecha_incidencia');

        }else {
            return Incidencia::query()
                ->select(DB::raw('incidencias.id,users.name,incidencias.folio,incidencias.estacion,estaciones.nombre_corto,incidencias.fecha_incidencia,areas_estacion.descripcion as Area_Estacion,equipos.descripcion as Equipo,incidencias.asunto,incidencias.descripcion,areas_atencion.descripcion as Area_Atencion,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,incidencias.posicion,incidencias.producto,refacciones.descripcion as Refaccion'))
                ->join('users','incidencias.id_usuario','=','users.id')
                ->join('estaciones','incidencias.estacion','=','estaciones.estacion')
                ->join('areas_estacion', function($join){
                    $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                    $join->on('incidencias.estacion','=','areas_estacion.estacion');
                })
                ->join('equipos', function($join){
                    $join->on('incidencias.id_equipo','=','equipos.id');
                    $join->on('incidencias.estacion','=','equipos.estacion');
                })
                ->join('areas_atencion','incidencias.id_area_atencion','=','areas_atencion.id')
                ->join('refacciones', function($join) use ($condicion_idEquipo1, $condicion_idEquipo2){
                    $join->on('incidencias.id_refaccion','=','refacciones.id');
                    $join->on('incidencias.estacion','=','refacciones.estacion');
                    //$join->on('incidencias.id_equipo','=','refacciones.id_equipo');
                    //$condicion_idEquipo; **CORPORATIVO
                    $join->on($condicion_idEquipo1,'=',$condicion_idEquipo2);
                })        
                ->where('incidencias.estacion','=',$this->estacion)
                ->whereRaw("cast(incidencias.fecha_incidencia as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")        
                ->orderBy('incidencias.estacion')
                ->orderBy('incidencias.estatus_incidencia') 
                ->orderBy('incidencias.fecha_incidencia'); 
        }
        
    }
}
