<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;


use Illuminate\Http\Response;

use Illuminate\Http\Request;
use App\anexo_ventas;
use App\Exports\Sheets\AnexoExport_Ventas;
use App\Exports\Sheets\AnexoExport_Compras;
use App\Exports\Sheets\AnexoExport_Dif;
use App\Exports\Sheets\AnexoExport_General;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\IncidenciaFormRequest;

use App\User;
use Illuminate\Support\Facades\Input;
//use DB;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Auth;


class AnexoExport implements ShouldAutoSize, WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    
    
    // public function __construct($estacion,$mes,$sucursal,$aux_mes,$compras_cant)
    // {
    //     $this->estacion = $estacion;
    //     $this->mes = $mes;
    //     $this->sucursal=$sucursal;
    //     $this->aux_mes=$aux_mes;
    //     $this->cant=$compras_cant;
    // }

    public function __construct($estacion,$sucursal,$fecha1,$fecha2,$compras_cant,$mes_letra)
    {
        $this->estacion = $estacion;
        $this->sucursal=$sucursal;
        $this->fecha_desde=$fecha1;
        $this->fecha_hasta=$fecha2;
        $this->cant=$compras_cant;
        $this->mes=$mes_letra;
    }

    public function sheets(): array{
        $sheets = [];
        // for ($month = 1; $month <= 4; $month++) {
        //     $sheets[] = new AnexoExport_Ventas($this->estacion, $month);
        // }

        $sheets[] = new AnexoExport_Ventas($this->estacion, $this->sucursal, $this->fecha_desde, $this->fecha_hasta, $this->mes);
        $sheets[] = new AnexoExport_Compras($this->estacion, $this->sucursal, $this->fecha_desde, $this->fecha_hasta, $this->cant, $this->mes);
        $sheets[] = new AnexoExport_Dif($this->estacion, $this->fecha_desde, $this->fecha_hasta, $this->mes);
        $sheets[]= new AnexoExport_General( $this->fecha_desde, $this->fecha_hasta, $this->mes);

        return $sheets;
    }

    
}
