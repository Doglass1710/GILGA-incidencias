<?php

namespace App\Exports\sheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\anexo_ventas;

use Illuminate\Support\Facades\DB;

class AnexoExport_Ventas implements FromQuery, WithHeadings, WithEvents, WithTitle,ShouldAutoSize
{
    
    public function __construct($estacion,$sucursal,$fecha1,$fecha2,$mes)
    {
        $this->estacion = $estacion;
        $this->sucursal  = $sucursal;
        $this->fecha_desde=$fecha1;
        $this->fecha_hasta=$fecha2;
        $this->mes_letra = $mes;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('E2:G2');

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
            //DESCRIPCIÓN SUCURSAL Y MES
            $event->sheet->getStyle('A2:N2')->applyFromArray([
                'font' => [
                    'bold' => true
                ]
            ]);
            //ENCABEZADOS REPORTE
                $event->sheet->getStyle('A3:N3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0B0B61']
                        ]
                ]);            

            }];
    } 
    public function headings(): array   
    {
        $encabezado1 = [];         
        $encabezado1[]="ANALISIS DE ESTADISTICAS DIARIAS"; 

        $encabezado2 = [];
        $encabezado2[]="Estación: ";
        $encabezado2[]="";
        $encabezado2[]=$this->estacion;
        $encabezado2[]="Sucursal: ";
        $encabezado2[]=$this->sucursal;
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="Mes: ";
        $encabezado2[]=$this->mes_letra;

        $encabezado3 = []; 
        $encabezado3[]="ID";
        $encabezado3[]="DIA";
        $encabezado3[]="PRODUCTO";
        $encabezado3[]="INV. INICIAL";
        $encabezado3[]="COMPRAS";
        $encabezado3[]="VENTAS";
        $encabezado3[]="VTAS. ACUM";
        $encabezado3[]="INV. TEORICO";
        $encabezado3[]="INV FINAL";
        $encabezado3[]="VARIACION DIA";
        $encabezado3[]="ACUM";
        $encabezado3[]="%VAR DIA";
        $encabezado3[]="A.C.";
        $encabezado3[]="ROT INV.";
        
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
        // $complemento="and CAST(fecha_diavencido AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ";

        // if($this->mes_num==12)
        // {
        //     $complemento="";
        // }

        $consulta_magna=DB::table("anexo_ventas_producto")
        ->select(DB::Raw("id,dia,producto,inv_inicial,compra,venta,venta_acum,inv_teorico,inv_final,
            Round(variacion,2) variacion,Acum,CONCAT(Round(porc_variacion,2),'%') porcen_variacion,  
            CONCAT(Round(ac,2),'%') ac, Round(rot_inv,2) rot_inv "))
        ->where('estacion','=',$this->estacion)
        ->where('producto','=','Magna')
        ->whereRaw("CAST(fecha_diavencido  AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");
       // ->whereRaw("CAST(fecha_diavencido  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01') ".$complemento);

        $consulta_premium=DB::table("anexo_ventas_producto")
        ->select(DB::Raw("id,dia,producto,inv_inicial,compra,venta,venta_acum,inv_teorico,inv_final,
            Round(variacion,2) variacion,Acum,CONCAT(Round(porc_variacion,2),'%') porcen_variacion,  
            CONCAT(Round(ac,2),'%') ac, Round(rot_inv,2) rot_inv "))
        ->where('estacion','=',$this->estacion)
        ->where('producto','=','Premium')
        ->whereRaw("CAST(fecha_diavencido  AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

            $consulta_diesel=DB::table("anexo_ventas_producto")
        ->select(DB::Raw("id,dia,producto,inv_inicial,compra,venta,venta_acum,inv_teorico,inv_final,
            Round(variacion,2) variacion,Acum,CONCAT(Round(porc_variacion,2),'%') porcen_variacion,  
            CONCAT(Round(ac,2),'%') ac, Round(rot_inv,2) rot_inv "))
        ->where('estacion','=',$this->estacion)
        ->where('producto','=','Diesel')
        ->whereRaw("CAST(fecha_diavencido  AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");
                    
        return 
            $consulta_magna
            ->union($consulta_premium)
                ->union($consulta_diesel)
                    ->OrderBy("id")
                        ->OrderBy("dia");                    
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ANEXO-' . $this->mes_letra;
    }
}
