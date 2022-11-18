<?php

namespace App\Exports;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Support\Facades\DB;

class Anexo_PlantillaInventario implements FromQuery, WithHeadings, WithEvents, WithTitle,ShouldAutoSize
{
    use Exportable;

    // public function __construct($estacion,$mes_num,$mes_letra)
    // {
    //     $this->estacion = $estacion;
    //     $this->mes_num=$mes_num;
    //    // $this->sucursal  = $sucursal;
    //     $this->mes_letra = $mes_letra;
    // }
    public function __construct($estacion,$fecha1,$fecha2)
    {
        $this->estacion = $estacion;
        $this->fecha_desde=$fecha1;
        $this->fecha_hasta = $fecha2;
    }

    public function registerEvents(): array
    {
        return[
        AfterSheet::class=>function(AfterSheet $event)
        {
            $event->sheet->mergecells('A1:J1');
            
            //FORMATO PARA TITULO BITACORA
            $event->sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '0B0B61']
                ]
            ]);

            //ENCABEZADOS REPORTE
            $event->sheet->getStyle('A2:J2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000']
                ],
                // 'fill' => [
                //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //     'color' => ['rgb' => '000000']
                //     ]
            ]); 

        }];
    }

    public function headings(): array   
    {
        $encabezado1 = [];         
        $encabezado1[]="PLANTILLA INVENTARIO ".$this->estacion; 

        $encabezado2=[];
        $encabezado2[]="Estación";
        $encabezado2[]="Nombre";
        $encabezado2[]="Fecha";
        $encabezado2[]="Inventario Regular";
        $encabezado2[]="Inventario Premium";
        $encabezado2[]="Inventario Diesel";
        $encabezado2[]="Ventas Regular";
        $encabezado2[]="Ventas Premium";
        $encabezado2[]="Ventas Diesel";
        $encabezado2[]="Fecha de captura";

        return [$encabezado1,$encabezado2];
    }

    public function query()
    {
        // $sig_mes=($this->mes_num+1);

        // if($sig_mes<10){
        //     $sig_mes="0".$sig_mes;
        // }
        // $complemento=" and CAST(v.fecha_diavencido AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ";

        // if($this->mes_num==12)
        // {
        //     $complemento="";
        // }

            if($this->estacion=="*")
            {
                $anexo=DB::table("vw_plantilla_inventario_reporte as v")
                ->SELECT(DB::raw("v.estacion,e.nombre_corto,DATE_FORMAT(v.fecha_diavencido, '%d/%m/%Y') as fecha, v.inventario_regular,v.inventario_premium,v.inventario_diesel,v.venta_regular,v.venta_premium,v.venta_diesel,DATE_FORMAT(v.fecha_captura, '%d/%m/%Y %H:%i')"))
                ->join('estaciones as e','e.estacion','=','v.estacion')
                //->whereRaw("CAST(v.fecha_diavencido AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01')  and CAST(v.fecha_diavencido AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ")
                //->whereRaw("CAST(v.fecha_diavencido  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01') ".$complemento)
                ->whereRaw("CAST(v.fecha_diavencido  AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
                ->OrderBy("v.fecha_diavencido");
            }
            else
            {
                $anexo=DB::table("vw_plantilla_inventario_reporte as v")
                ->SELECT(DB::raw("v.estacion,e.nombre_corto,DATE_FORMAT(v.fecha_diavencido, '%d/%m/%Y') as fecha, v.inventario_regular,v.inventario_premium,v.inventario_diesel,v.venta_regular,v.venta_premium,v.venta_diesel,DATE_FORMAT(v.fecha_captura, '%d/%m/%Y %H:%i')"))
                ->join('estaciones as e','e.estacion','=','v.estacion')
                ->where('e.estacion','=',$this->estacion)
                //->whereRaw("CAST(v.fecha_diavencido AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01') ".$complemento)
                ->whereRaw("CAST(v.fecha_diavencido  AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
                ->OrderBy("v.fecha_diavencido");
            }

            return $anexo;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Nivel de Inventario y Ventas';
    }


}