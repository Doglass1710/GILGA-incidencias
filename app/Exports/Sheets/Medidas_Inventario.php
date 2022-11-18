<?php
namespace App\Exports\Sheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Medidas_Inventario implements FromQuery, WithTitle, WithEvents, WithHeadings, ShouldAutoSize
{

    public function __construct($estacion,$fecha_desde,$fecha_hasta)
    {
        $this->estacion = $estacion;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
    }

    public function registerEvents(): array
    {
        return[
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->mergecells('A1:E1');

                $event->sheet->getStyle('A2:E2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'], 
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '0174DF']
                    ]
                ]);
                $event->sheet->getStyle('A1:E1')->applyFromArray([
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
                $event->sheet->getStyle('C2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '04B404']
                        ]
                ]);

                $event->sheet->getStyle('D2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FE2E2E']
                        ]
                ]);

                $event->sheet->getStyle('E2')->applyFromArray([
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
        $encabezado1=[];
        $encabezado1[]="INVENTARIOS";

        $encabezado2=[];
        $encabezado2[]="ESTACION";
        $encabezado2[]="SUCURSAL";
        $encabezado2[]="D. I MAGNA";
        $encabezado2[]="D. I PREMIUM";
        $encabezado2[]="D. I DIESEL";

        return [$encabezado1,$encabezado2];
    }

    public function query(){

        if($this->estacion == '*'){

            $VistaTransportes=DB::table('vw_medidas_vs_anexo_reporte')
            ->select(DB::raw('estacion,sucursal,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
            ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
            ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

            $VistaPemex=DB::table('vw_medidas_vs_anexo_reporte')
            ->select(DB::raw('estacion,sucursal,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
            ->where('Tanque_Descripcion','=','PEMEX')
            ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

            $VistaRepsol=DB::table('vw_medidas_vs_anexo_reporte')
            ->select(DB::raw('estacion,sucursal,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
            ->where('Tanque_Descripcion','=','REPSOL')
            ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");           
            
            }else {
                
                $VistaTransportes=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('estacion,sucursal,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                ->where('estacion','=',$this->estacion)
                ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                $VistaPemex=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('estacion,sucursal,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                ->where('Tanque_Descripcion','=','PEMEX')
                ->where('estacion','=',$this->estacion)
                ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");

                $VistaRepsol=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('estacion,sucursal,Round((inv_magna/venta_magna),2) d_magna,Round((inv_premium/venta_premium),2) d_premium,Round((inv_diesel/venta_diesel),2) d_diesel'))
                ->where('Tanque_Descripcion','=','REPSOL')
                ->where('estacion','=',$this->estacion)
                ->whereRaw("cast(fecha_captura as date) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");
                
            } 
        return $VistaTransportes
        ->union($VistaPemex)
        ->union($VistaRepsol)
        ->orderByRaw('estacion');
    }

    public function title(): string
    {
        return "Dias_Inventario";
    }

}
