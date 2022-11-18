<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    protected $table = 'compras';
    protected $pk = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id_incidencia',        
        'id_usuario',      
        'fecha_compra',
        'proveedor',
        'facturar_a',
        'folio',       
        'observaciones',
        'usuario_autoriza',        
        'autorizada_sn',
        'cerrada_sn',
        'subtotal',
        'iva',
        'total',
        
    ];
    
    protected $guarded = [
        
    ];
}
