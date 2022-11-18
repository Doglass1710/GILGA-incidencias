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
use App\Compras;
use App\Compra_Detalle;

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


class ComprasExport implements FromQuery, ShouldAutoSize, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }    
    
    public function __construct($tipo_reporte,$fecha_desde,$fecha_hasta)
    {
        //$this->estacion = $estacion;
        $this->tipo=$tipo_reporte;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
    }
    
    public function headings(): array
    {
        if($this->tipo=='1'){
            return [
                'Id Compra',
                'Estacion',
                'Fecha Compra',
                'ID Incidencia',
                'Usuario',
                'Proveedor',
                'Facturar A',
                'Folio',
                'Observaciones',
                'Usuario Autoriza',
                'Autorizada',
                'Subtotal',
                'IVA',
                'Total',

            ];
        }elseif($this->tipo=='2'){
            return [
                'ID Incidencia',
                'ID Compra',
                'Folio',
                'Estacion',
                'Asunto',
                'Descripcion',
                'Tipo Producto',
                'Area de Atención',
                'Compra',
                'Cantidad',
                'Precio Unitario',
                'Total',
                'Fecha de Compra',
                'Fecha de incidencia'
            ];
        }else{
            return [
                'ID Incidencia',
                'Folio',
                'Estacion',
                'Asunto',
                'Descripcion',
                'Tipo Producto',
                'Area de Atención',
                'Fecha de incidencia',
                'Estatus Incidencia'
            ];
        }
    }
    
    public function query()
    {

        if($this->tipo=='1'){

            return Compras::query()
                            ->select(DB::raw('compras.id as id_compra,inc.estacion,compras.fecha_compra,compras.id_incidencia,
                            u.name as id_usuario,p.razon_social as proveedor,f.razon_social as facturar_a,compras.folio,
                            compras.observaciones,IFNULL(us.name,"") as usuario_autoriza,compras.autorizada_sn,
                            compras.subtotal,compras.iva,compras.total'))
                            ->join('users as u','compras.id_usuario','=','u.id')
                            ->leftJoin('users as us','compras.usuario_autoriza','=','us.id')
                            ->join('proveedores as p','compras.proveedor','=','p.proveedor')
                            ->join('companias as f','compras.facturar_a','=','f.id')
                            ->join('incidencias as inc','inc.id','=','compras.id_incidencia')                
                            ->whereRaw("cast(compras.fecha_compra as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
                            //->where('compras.cerrada_sn','=','SI')
                            ->union(
                                Compra_Detalle::select(DB::raw('compras_detalle.id_compra as id_compra,"","","","",concat("ID Incidencia: ",compras_detalle.id_incidencia),concat("Unidad: ",compras_detalle.unidad),concat("Tipo Cambio: ",compras_detalle.tipo_cambio),concat("Moneda: ",compras_detalle.moneda),concat("Producto: ",compras_detalle.producto_descripcion),concat("Cantidad: ",compras_detalle.cantidad),concat("Precio Unitario: ",compras_detalle.precio_unitario),concat("Total: ",compras_detalle.total),"" '))
                                ->join('compras as com','compras_detalle.id_compra','=','com.id')
                                ->whereRaw("cast(com.fecha_compra as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
                                //->where('com.cerrada_sn','=','SI')
                                
                            )
                            ->orderBy('id_compra')
                            ->orderByRaw('fecha_compra DESC');                          
                    
        }elseif($this->tipo=='2'){

            return Incidencia::query()
                ->select(DB::raw('cd.id_incidencia,cd.id_compra,incidencias.folio,incidencias.estacion,incidencias.asunto,r.descripcion,incidencias.producto "Tipo Producto",a.descripcion "Area_Atencion", 
                cd.producto_descripcion "Compra",concat(cd.cantidad," ",cd.unidad) cantidad , cd.precio_unitario,cd.total,cd.created_at "fecha_compra",incidencias.fecha_incidencia'))
                ->join('compras_detalle as cd','incidencias.id','=','cd.id_incidencia')
                ->leftjoin ('refacciones as r', function($join){
                    $join->on('incidencias.id_refaccion','=','r.id');
                    $join->on('incidencias.estacion','=','r.estacion');
                    $join->on('incidencias.id_equipo','=','r.id_equipo');
                    $join->on('incidencias.id_area_atencion','=','r.id_area_atencion');
                })
                ->join('areas_atencion as a','incidencias.id_area_atencion','=','a.id')
                
                //->where('incidencias.id_area_atencion=3')
                ->whereRaw("cast(cd.created_at as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
                ->orderBy("cd.created_at")
                ->orderBy("incidencias.estacion")
                ->orderBy("incidencias.fecha_incidencia");  
        }else{

            return Incidencia::query()
            ->select(DB::raw('incidencias.id,incidencias.folio,incidencias.estacion,incidencias.asunto,r.descripcion,incidencias.producto "Tipo Producto",
            a.descripcion "Area de Atención", incidencias.fecha_incidencia,incidencias.estatus_incidencia'))
            ->leftjoin ('refacciones as r', function($join){
                $join->on('incidencias.id_refaccion','=','r.id');
                $join->on('incidencias.estacion','=','r.estacion');
                $join->on('incidencias.id_equipo','=','r.id_equipo');
                $join->on('incidencias.id_area_atencion','=','r.id_area_atencion');
            })
            ->join('areas_atencion as a','incidencias.id_area_atencion','=','a.id')
            ->whereRaw('incidencias.id not in ( select id_incidencia from compras_detalle ) and incidencias.estatus_incidencia="cerrada" and incidencias.id_area_atencion=3')
            ->whereRaw("cast(incidencias.fecha_incidencia as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
            ->orderBy('incidencias.fecha_incidencia');

        }

        
    }
}
