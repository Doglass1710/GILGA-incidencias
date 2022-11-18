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
                'Subarea',
                'Asunto',
                'Descripcion',
                'Area Atencion',
                'Estatus',
                'Tipo Solicitud',
                'Prioridad',
                'Posicion',
                'Cantidad',
                'Refaccion/Equipo',
                'Fecha Ultima Actualizacion',
                'Fecha Cierre',
                'Dias Vida Incidencia',
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
        if($this->estacion == '*')
        {
            $Vista=DB::table('vw_listado_incidencias as i')
            ->select(DB::raw('i.id,u.name,folio,estacion,nombre_corto,fecha_incidencia,
                case when i.posicion=0 then (select descripcion from inventario_areas where id=i.id_area_estacion) else area_estacion_descripcion end area_estacion_descripcion, 
                case when i.posicion=0 then (select descripcion from inventario_subareas where id=i.id_refaccion) else subarea end equipo_descripcion,
                asunto,descripcion,
                area_atencion_descripcion,estatus_incidencia,tipo_solicitud,
                prioridad,posicion,cantidad,
                case when i.posicion=0 then (select descripcion from inventario_equipos where id=i.id_equipo) else refaccion_descripcion end refaccion_descripcion,
                fecha_ultima_actualizacion,fecha_cierre,dias_vida_incidencia'))
            ->whereRaw('estacion not in ("CORPORATIVO","H. INDIGO")') 
            ->whereRaw("cast(fecha_incidencia as date) between cast('". $this->fecha_desde . "' as date) and cast('". $this->fecha_hasta . "' as date)")
            ->join('users as u','u.id','=','i.id_usuario')
            ->orderBy('estacion')
            ->orderBy('estatus_incidencia') 
            ->orderBy('fecha_incidencia');

        }else{
            $Vista=DB::table('vw_listado_incidencias as i')
            ->select(DB::raw('i.id,u.name,folio,estacion,nombre_corto,fecha_incidencia,
                case when i.posicion=0 then (select descripcion from inventario_areas where id=i.id_area_estacion) else area_estacion_descripcion end area_estacion_descripcion, 
                case when i.posicion=0 then (select descripcion from inventario_subareas where id=i.id_refaccion) else subarea end equipo_descripcion,
                asunto,descripcion,
                area_atencion_descripcion,estatus_incidencia,tipo_solicitud,
                prioridad,posicion,cantidad,
                case when i.posicion=0 then (select descripcion from inventario_equipos where id=i.id_equipo) else refaccion_descripcion end refaccion_descripcion,
                fecha_ultima_actualizacion,fecha_cierre,dias_vida_incidencia'))
            ->where('estacion','=',$this->estacion)
            ->whereRaw("cast(fecha_incidencia as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")            
            ->join('users as u','u.id','=','i.id_usuario')
            ->orderBy('estacion')
            ->orderBy('estatus_incidencia') 
            ->orderBy('fecha_incidencia');
        }
        return $Vista;
    }
}
