<?php

namespace App\Exports;

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Medidas_Diarias;
use App\Exports\Sheets\Medidas_DiariasExport;
use App\Exports\Sheets\Medidas_Inventario;


class MedidasExport implements WithMultipleSheets
{
    use Exportable;
   
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
    
    public function sheets(): array
    {
        $sheets=[];
        $sheets[]= new Medidas_DiariasExport
            (
                $this->estacion,
                $this->fecha_desde,
                $this->fecha_hasta,
                $this->sebas,
                $this->pemex,
                $this->repsol,        
                $this->tipo_reporte
            );
        // if($this->tipo_reporte=="con_ventas")
        // {
        // $sheets[]=new Medidas_Inventario
        //     (
        //         $this->estacion,
        //         $this->fecha_desde,
        //         $this->fecha_hasta
        //     );
        // }

        return $sheets;
    }
}
