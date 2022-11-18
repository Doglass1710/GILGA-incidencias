<?php

namespace App\Exports\sheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\anexo_compras;



class AnexoExport_General implements FromQuery, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{

    public function __construct($fecha1,$fecha2,$mes)
    {
        $this->fecha_desde=$fecha1;
        $this->fecha_hasta=$fecha2;
        $this->mes_letra = $mes;       
    }

    public function registerEvents(): array
    {
        return[
        AfterSheet::class=>function(AfterSheet $event)
        {
            $event->sheet->mergecells('A1:F1');
            
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

            //DESCRIPCIÓN  MES
            $event->sheet->getStyle('A2:F2')->applyFromArray([
                'font' => [
                    'bold' => true
                ]
            ]);

            //ENCABEZADOS REPORTE
            $event->sheet->getStyle('A3:F3')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '0B0B61']
                    ]
            ]);
        }
        ];
    }

    public function headings(): array   
    {
        $encabezado1 = [];         
        $encabezado1[]="BITACORA DE COMPRAS GENERAL"; 

        $encabezado2=[];
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="MES: ";
        $encabezado2[]=$this->mes_letra;

        $encabezado3=[];
        $encabezado3[]="ESTACION";
        $encabezado3[]="FECHA DE COMPRA";
        $encabezado3[]="PRODUCTO";
        $encabezado3[]="PRECIO DE COMPRA C/ IVA";
        $encabezado3[]="LITROS";
        $encabezado3[]="PRECIO DE COMPRA";

        return [$encabezado1,$encabezado2,$encabezado3];
    }
    
    /**
     * @return Builder
     */
    public function query()
    {
        // $sig_mes=($this->mes_num+1);
        // if($sig_mes<10){
        //     $sig_mes="0".$sig_mes;
        // }
        // $complemento="and CAST(a.fecha AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ";

        // if($this->mes_num==12)
        // {
        //     $complemento="";
        // }

        $compras=DB::table("anexo_compras as a")
        ->SELECT(DB::raw("e.nombre_corto estacion,a.fecha,producto,Round(a.precioLitroIva,2) precioLitroIva,a.litros,Round(a.precioLitro,2) precioLitro"))
        ->join('estaciones as e','e.estacion','=','a.estacion')
        //->whereRaw("CAST(a.fecha AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01') ".$complemento)
        ->whereRaw("CAST(a.fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
        ->OrderBy("fecha");
        return $compras;

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'BITACORA DE COMPRAS-' . $this->mes_letra;
    }
}
