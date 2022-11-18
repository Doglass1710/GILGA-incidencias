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
use App\Bitacora_mtto;
use App\Bitacora_mtto_detalle;
use App\Estacion;

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


class BitacoraExport implements FromQuery, ShouldAutoSize, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:H1');

            //FORMATO PARA TITULO BITACORA
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FE2E2E']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ]
                ]);
            //ENCABEZADOS REPORTE
                $event->sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '000000']
                        ]
                ]);            

            }];
    }    
    
    public function __construct($estacion,$fecha_desde,$fecha_hasta,$vehiculo,$sucursal)
    {
        $this->estacion = $estacion;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
        $this->vehiculo=$vehiculo;
        $this->sucursal=$sucursal;
    }


    public function headings(): array
    {
        return [
            $this->sucursal." , ".$this->vehiculo,
        ];
    }
    
    
    public function query()
    {
          return Bitacora_mtto_detalle::query()
                ->select(DB::raw("'Cantidad' Cantidad,'Unidad' Unidad,'Descripcion' Descripcion,'PrecioUnitario' PrecioUnitario,'Importe' Importe,'IVA' IVA,'Total' Total,'Fecha_Bitacora' Fecha_Bitacora"))
                ->union(
                    Bitacora_mtto_detalle::query()
                    ->select(DB::raw('bitacora_mtto_detalle.cantidad,bitacora_mtto_detalle.unidad,c.descripcion,
                        Round (bitacora_mtto_detalle.importe, 2) PrecioUnitario,
                        Round ((bitacora_mtto_detalle.importe*bitacora_mtto_detalle.cantidad), 2) Importe,
                        Round (bitacora_mtto_detalle.iva, 2) IVA,
                        Round(bitacora_mtto_detalle.total,2) total,
                        DATE_FORMAT(m.fecha_bitacora, "%d-%m-%Y") Fecha_Bitacora'))
                    ->join('bitacora_catalogo as c','c.id','=','bitacora_mtto_detalle.refaccion')
                    ->join('bitacora_mtto as m','m.id','=','bitacora_mtto_detalle.id_bitacora')
                    ->join('bitacora_vehiculos as v','v.id','=','m.id_vehiculo')
                    ->where('v.estacion','=',$this->estacion)
                    ->whereRaw("cast(m.fecha_bitacora as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
                )
                ->orderBy('Fecha_Bitacora', 'desc')
                ->orderBy('cantidad', 'desc');
        
    }
}
