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



class AnexoExport_Compras implements FromQuery, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{
    
        
    //$this->cell("F27","HOLA");

    // public function __construct($estacion,$mes_num,$sucursal,$mes_letra,$compras_cant)
    // {
    //     $this->estacion = $estacion;
    //     $this->mes_num=$mes_num;
    //     $this->sucursal  = $sucursal;
    //     $this->mes_letra = $mes_letra;
    //     $this->cant=$compras_cant;
    // }

    public function __construct($estacion,$sucursal,$fecha1,$fecha2,$compras_cant,$mes)
    {
        $this->estacion = $estacion;
        $this->sucursal  = $sucursal;
        $this->fecha_desde=$fecha1;
        $this->fecha_hasta=$fecha2;
        $this->cant=$compras_cant;
        $this->mes_letra = $mes;
    }

    public function registerEvents(): array
    {
        return[
        AfterSheet::class=>function(AfterSheet $event)
        {
            $event->sheet->mergecells('A1:D1');
            $event->sheet->mergecells('F3:H3');

            
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
            $event->sheet->getStyle('E2:F2')->applyFromArray([
                'font' => [
                    'bold' => true
                ]
            ]);

            //ENCABEZADOS REPORTE
            $event->sheet->getStyle('A3:W4')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '0B0B61']
                    ]
            ]);

            //MAGNA-PREMIUM-DIESEL
            $fila=($this->cant + 4);
            $event->sheet->getStyle('F5:F'.$fila)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ], 
                'fill'=>[
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '04B404']
                ]
            ]);
            $event->sheet->getStyle('G5:G'.$fila)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'], 
                ],
                'fill'=>[
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FE2E2E']
                ]
            ]);
            $event->sheet->getStyle('H5:H'.$fila)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'], 
                ],
                'fill'=>[
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '000000']
                ]
            ]);

            //esconder valores para ordenamiento en consulta: totales
            $fila=($this->cant + 5);
            $event->sheet->getStyle('A'.$fila.':D'.$fila)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'], 
                ]
            ]);

            //Remarcar totales
            $fila=($this->cant + 5);
            $event->sheet->getStyle('F'.$fila.':H'.$fila)->applyFromArray([
                'font' => [
                    'bold' => true
                ]
            ]);
        }
        ];
    }

    public function headings(): array   
    {
        $encabezado1 = [];         
        $encabezado1[]="BITACORA DE COMPRAS RECIBIDAS"; 

        $encabezado2=[];
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="";
        $encabezado2[]="MES: ";
        $encabezado2[]=$this->mes_letra;

        $encabezado3=[];
        $encabezado3[]="";
        $encabezado3[]="";
        $encabezado3[]="";
        $encabezado3[]="";
        $encabezado3[]="";
        $encabezado3[]="LITROS S/G FACTURA";
        $encabezado3[]="";
        $encabezado3[]="";
        $encabezado3[]="SEGUN VEEDER ROOT";

        $encabezado4=[];
        $encabezado4[]="No TANQUE";
        $encabezado4[]="No ECO.";
        $encabezado4[]="NOMBRE DEL OPERADOR";
        $encabezado4[]="FECHA";
        $encabezado4[]="IMPORTE";
        $encabezado4[]="MAGNA";
        $encabezado4[]="PREMIUM";
        $encabezado4[]="DIESEL";
        $encabezado4[]="FOLIO PMX";
        $encabezado4[]="INICIA";
        $encabezado4[]="TERMINA";
        $encabezado4[]="TIEMPO DESCARGA";
        $encabezado4[]="VOL. INICIAL";
        $encabezado4[]="VOL. FINAL";
        $encabezado4[]="VOL. DESCARGA";
        $encabezado4[]="DESCARGA";
        $encabezado4[]="MAGNA";
        $encabezado4[]="PREMIUM";
        $encabezado4[]="DIESEL";
        $encabezado4[]="MS";
        $encabezado4[]="PM";
        $encabezado4[]="DL";
        $encabezado4[]="VIGENCIA";
        $encabezado4[]="ID";

        return [$encabezado1,$encabezado2,$encabezado3,$encabezado4];
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
        // $complemento="and CAST(fecha AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ";

        // if($this->mes_num==12)
        // {
        //     $complemento="";
        // }

            $fila=($this->cant + 4);
            // case when (vol_final-vol_inicial) = '' then 0 else (vol_final-vol_inicial) end

            $compras_magna=DB::table("anexo_compras")
            ->select(DB::raw("CASE WHEN estacion='6571' THEN concat(no_tanque,' (',cubetas,' CUBETAS DE 19 LTS)') ELSE no_tanque END no_tanque,
                no_Eco,operador,fecha,importe,litros LtsMagna,'' LtsPremium,'' LtsDiesel,folioPMX,inicia,termina,tiempo_descarga, vol_inicial, 
                vol_final,vol_descarga, venta_descarga,(-litros+vol_descarga) Magna,'' Premium,'' Diesel, '' MS, '' PM, '' DL, '' Vigencia, ID"))
            ->where('estacion','=',$this->estacion)
            ->whereRaw("CAST(fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
            //->whereRaw("CAST(fecha  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01') ".$complemento)
            ->where('producto','=','Magna');

            $compras_premium=DB::table("anexo_compras")
            ->select(DB::raw("CASE WHEN estacion='6571' THEN concat(no_tanque,' (',cubetas,' CUBETAS DE 19 LTS)') ELSE no_tanque END no_tanque,
                no_Eco,operador,fecha,importe,'' LtsMagna,litros LtsPremium,'' LtsDiesel,folioPMX,inicia,termina,tiempo_descarga, vol_inicial,
                vol_final, vol_descarga,venta_descarga,'' Magna,(-litros+vol_descarga) Premium,'' Diesel, '' MS, '' PM, '' DL, '' Vigencia, ID"))
            ->where('estacion','=',$this->estacion)
            ->whereRaw("CAST(fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
            ->where('producto','=','Premium');

            $compras_diesel=DB::table("anexo_compras")
            ->select(DB::raw("CASE WHEN estacion='6571' THEN concat(no_tanque,' (',cubetas,' CUBETAS DE 19 LTS)') ELSE no_tanque END no_tanque,
                no_Eco,operador,fecha,importe,'' LtsMagna,'' LtsPremium,litros LtsDiesel,folioPMX,inicia,termina,tiempo_descarga, vol_inicial,
                vol_final, vol_descarga,venta_descarga,'' Magna,'' Premium,(-litros+vol_descarga) Diesel, '' MS, '' PM, '' DL, '' Vigencia, ID"))
            ->where('estacion','=',$this->estacion)
            ->whereRaw("CAST(fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
            ->where('producto','=','Diesel');

            $totales=DB::table("anexo_compras")
                     ->select(DB::raw('"50","","",DATE_ADD(MAX(fecha_captura),INTERVAL 1 DAY),"",
                     "=SUM(F5:F'.$fila.')","=SUM(G5:G'.$fila.')","=SUM(H5:H'.$fila.')","","","","","","","","","","","","","","","",""'));
           
            return $compras_magna
            ->union($compras_premium)
            ->union($compras_diesel)
            ->union($totales)
            ->OrderBy("fecha")
            ->OrderBy("no_tanque");

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'COMPRAS-' . $this->mes_letra;
    }
}
