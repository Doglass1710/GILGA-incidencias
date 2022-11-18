<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra_Detalle extends Model
{
    protected $table = 'compras_detalle';
    protected $pk = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id_compra',
        'id_incidencia',        
        'cantidad',
        'unidad',
        'producto_descripcion',
        'tipo_cambio',
        'moneda',
        'precio_unitario',
        'total',       
        
    ];
    
    protected $guarded = [
        
    ];
}
