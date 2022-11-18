<?php
namespace App\Exports\Sheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Medidas_DiariasExport implements FromQuery, WithTitle, WithEvents, WithHeadings, ShouldAutoSize
{

    public function __construct($estacion,$fecha_desde,$fecha_hasta,$sebas,$pemex,$repsol,$tipo_reporte)
    {
        $this->estacion = $estacion;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
        $this->sebas=$sebas;
        $this->pemex=$pemex;
        $this->repsol=$repsol;
        $this->tipo_reporte=$tipo_reporte;
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->mergecells('A1:M1');
                $event->sheet->mergecells('N1:P1');

                if($this->tipo_reporte=='solo_medidas'){
                    $columna="Q";
                }
                else{
                    $columna="Z";
                    $event->sheet->mergecells('R1:T1');
                    $event->sheet->mergecells('U1:W1');
                    $event->sheet->mergecells('X1:Z1');
                }

                $event->sheet->getStyle('A2:'.$columna.'2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'], 
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0174DF']
                        ]
                ]);
            //ENCABEZADOS VERDE PARA MAGNA
                $event->sheet->getStyle('D2:E2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '04B404']
                        ]
                ]);
                $event->sheet->getStyle('K2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '04B404']
                        ]
                ]);

            //ENCABEZADOS PARA PREMIUM
                $event->sheet->getStyle('F2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FE2E2E']
                        ]
                ]);
                $event->sheet->getStyle('L2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FE2E2E']
                        ]
                ]);

            //ENCABEZADOS PARA DIESEL
                $event->sheet->getStyle('G2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0B0B61']
                        ]
                ]);
                $event->sheet->getStyle('M2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0B0B61']
                        ]
                ]);

            //ENCABEZADOS PARA INVENTARIOS - VENTA - DIAS INVENTARIO
            if($this->tipo_reporte=='con_ventas')
            {
                $event->sheet->getStyle('R1:Z1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'], 
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0B0B61']
                        ]
                ]);

                $event->sheet->getStyle('X2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '04B404']
                        ]
                ]);

                $event->sheet->getStyle('Y2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FE2E2E']
                        ]
                ]);

                $event->sheet->getStyle('Z2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0B0B61']
                        ]
                ]);
            }

            //ENCABEZADOS CUERPO
                $cant=($this->sebas)+2;          //+(num de encabezados)
                $event->sheet->getStyle('A3:Q'.$cant)->applyFromArray([
                    'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'ABCDEF']  //84CEE1
                    ]
                ]);

                $inicio=($cant)+1;  
                $cant=($this->repsol)+$cant;      
                $event->sheet->getStyle('A'.$inicio.':Q'.$cant)->applyFromArray([
                    'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'EEC831']
                    ]
                ]);

                $inicio=($cant)+1;   
                $cant=($this->pemex)+$cant;      
                $event->sheet->getStyle('A'.$inicio.':Q'.$cant)->applyFromArray([
                    'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '41B945']
                    ]
                ]);
            }];
    }    

    public function headings(): array
    {

        $encabezado1=[];
        $encabezado1[]="MEDIDAS DIARIAS AL ".$this->fecha_hasta;
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="";
        $encabezado1[]="OBSERVACIONES";
        $encabezado1[]="";
        $encabezado1[]="";        
        $encabezado1[]="";

        if($this->tipo_reporte=='con_ventas')
        {
            $encabezado1[]="INVENTARIOS";
            $encabezado1[]="";
            $encabezado1[]="";
            $encabezado1[]="VENTA";
            $encabezado1[]="";
            $encabezado1[]="";
            $encabezado1[]="DIAS DE INVENTARIO";
        }

        $encabezado2=[];
        $encabezado2[]="Tanque_Descripcion";
        $encabezado2[]="Estacion";
        $encabezado2[]="Sucursal";
        $encabezado2[]="Magna";
        $encabezado2[]="Magna T2";
        $encabezado2[]="Premium";
        $encabezado2[]="Diesel";
        $encabezado2[]="Tanque_Magna";
        $encabezado2[]="Tanque_Premium";
        $encabezado2[]="Tanque_Diesel";
        $encabezado2[]="Pipa_magna";
        $encabezado2[]="Pipa_premium";
        $encabezado2[]="Pipa_diesel";
        $encabezado2[]="Obs_magna";
        $encabezado2[]="Obs_premium";
        $encabezado2[]="Obs_diesel";
        $encabezado2[]="Fecha de Captura";

        if($this->tipo_reporte=='con_ventas'){

            $encabezado2[]="I_MAGNA";
            $encabezado2[]="I_PREMIUM";
            $encabezado2[]="I_DIESEL";
            $encabezado2[]="V_MAGNA";
            $encabezado2[]="V_PREMIUM";
            $encabezado2[]="V_DIESEL";
            $encabezado2[]="D. I MAGNA";
            $encabezado2[]="D. I PREMIUM";
            $encabezado2[]="D. I DIESEL";
        }

        return [$encabezado1,$encabezado2];        
    }


    public function query()
    {

            if($this->tipo_reporte=='solo_medidas'){
                
                if($this->estacion == '*'){
                    $VistaTransportes=DB::table('vw_medidas_diarias_reporte')
                    ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                    $VistaPemex=DB::table('vw_medidas_diarias_reporte')
                    ->where('Tanque_Descripcion','=','PEMEX')
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                    $VistaRepsol=DB::table('vw_medidas_diarias_reporte')
                    ->where('Tanque_Descripcion','=','REPSOL')
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");                   

                }else{

                    $VistaTransportes=DB::table('vw_medidas_diarias_reporte')
                    ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                    ->where('estacion','=',$this->estacion)
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                    $VistaPemex=DB::table('vw_medidas_diarias_reporte')
                    ->where('Tanque_Descripcion','=','PEMEX')
                    ->where('estacion','=',$this->estacion)
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                    $VistaRepsol=DB::table('vw_medidas_diarias_reporte')
                    ->where('Tanque_Descripcion','=','REPSOL')
                    ->where('estacion','=',$this->estacion)
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                }
                
            }
            else
            {

                if($this->estacion == '*'){

                $VistaTransportes=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('*,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                $VistaPemex=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('*,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                ->where('Tanque_Descripcion','=','PEMEX')
                ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                $VistaRepsol=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('*,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                ->where('Tanque_Descripcion','=','REPSOL')
                ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");           
                
                }else {
                    
                    $VistaTransportes=DB::table('vw_medidas_vs_anexo_reporte')
                    ->select(DB::raw('*,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                    ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                    ->where('estacion','=',$this->estacion)
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                    $VistaPemex=DB::table('vw_medidas_vs_anexo_reporte')
                    ->select(DB::raw('*,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                    ->where('Tanque_Descripcion','=','PEMEX')
                    ->where('estacion','=',$this->estacion)
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                    $VistaRepsol=DB::table('vw_medidas_vs_anexo_reporte')
                    ->select(DB::raw('*,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                    ->where('Tanque_Descripcion','=','REPSOL')
                    ->where('estacion','=',$this->estacion)
                    ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");
                    
                } 
            }
            
            return $VistaTransportes
            ->union($VistaPemex)
            ->union($VistaRepsol)
            ->orderByRaw('Tanque_Descripcion desc');        
    }

    public function title(): string
    {
        return 'Medidas_Diarias';
    }


}
