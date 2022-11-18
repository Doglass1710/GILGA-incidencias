<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sisa_captura extends Model
{
    protected $table = 'sisa_captura';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario',
        'estacion',        
        'gerente',
        'operador',
        'fecha',
        'hora',
        'producto',
        'volumen',
        'placas',
        'remision',
        'factura',
        'volumen_inicial',
        'volumen_final',
        'aumento',
        'venta',
        'cubetas'
    ];
    
    protected $guarded = [
        
    ];
}
