<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class anexo_compras extends Model
{
    protected $table = 'anexo_compras';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario',
        'estacion',        
        'no_tanque',
        'no_eco',
        'operador',
        'fecha',
        'importe',
        'producto',
        'litros',
        'folioPMX',
        'inicia',
        'termina',
        'tiempo_descarga',
        'vol_inicial',
        'vol_final',
        'vol_descarga',
        'venta_descarga'
    ];
    
    protected $guarded = [
        
    ];
}
