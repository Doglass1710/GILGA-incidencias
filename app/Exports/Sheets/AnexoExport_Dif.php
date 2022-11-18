<?php

namespace App\Exports\sheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\anexo_diferencia;

class AnexoExport_Dif implements FromQuery, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{

    public function __construct($estacion,$fecha1,$fecha2,$mes)
    {
        $this->estacion  = $estacion;
        $this->fecha_desde=$fecha1;
        $this->fecha_hasta=$fecha2;
        $this->mes_letra = $mes;
    }

    public function registerEvents(): array{
        return[
            AfterSheet::class=>function(AfterSheet $event){
                $event->sheet->mergecells('A1:F1');
                

                //ENCABEZADOS REPORTE
                $event->sheet->getStyle('A1:F2')->applyFromArray([
                    'font'=>[
                        'bold'=>true,
                        'color'=>['rgb'=>'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ],
                    'fill'=>[
                        'fillType'=> \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'=>['rgb'=> '0B0B61']
                    ]
                ]);

            }
        ];
    }

    public function headings(): array   
    {
        $encabezado1=[];
        $encabezado1[]="DIFERENCIAS";

        $encabezado2=[];
        $encabezado2[]="DIA";
        $encabezado2[]="TURNO";
        $encabezado2[]="DIF DE DINERO";
        $encabezado2[]="ACUMULADO";
        $encabezado2[]="DIFERENCIA";
        $encabezado2[]="TOTAL";
        
        return [$encabezado1,$encabezado2];
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

        $headers=DB::table('anexo_diferencia')
        ->select(DB::raw("CASE WHEN (date_format(fecha,'%d')-1) <= 10 THEN concat('0',(date_format(fecha,'%d')-1),'DIA') 
        ELSE concat((date_format(fecha,'%d')-1),'DIA') END 'DIA','TURNO','DIF DE DINERO','ACUMULADO','DIFERENCIA','TOTAL'")) 
        ->where('estacion','=', $this->estacion) 
        //->whereRaw("CAST(fecha AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$this->mes_num."-01') ".$complemento)
        ->whereRaw("CAST(fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
        ->groupBy('fecha');

        $total=DB::table('anexo_diferencia as a')
        ->select(DB::raw("date_format(fecha,'%d') dia,max(turno)+1 turno,'','','',sum(diferencia) total"))
        ->where("estacion","=",$this->estacion)
        ->whereRaw("CAST(fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)")
        ->groupBy("fecha");

        $consulta_dif=DB::table('anexo_diferencia as a')
        ->select(DB::raw("date_format(fecha,'%d') dia, turno,diferencia_litros,acumulado,Round((diferencia_litros-acumulado),2) diferencia,'' total"))
        ->where('estacion','=', $this->estacion)
        ->whereRaw("CAST(fecha AS DATE) between cast('$this->fecha_desde' as date) and cast('$this->fecha_hasta' as date)");
        

        return $consulta_dif
            ->union($total)
                ->OrderBy('dia')
                    ->OrderBy('turno');

        // return anexo_diferencia::query()
        // ->select(DB::raw("date_format(fecha,'%d') dia, turno,diferencia_litros,acumulado,Round((diferencia_litros-acumulado),2) diferencia"))
        // ->where('estacion','=', $this->estacion)
        // ->OrderBy('dia')
        // ->OrderBy('turno');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'DIFCOL-' . $this->mes_letra;
    }
}
