<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class anexo_ventas_producto extends Model
{
    protected $table = 'anexo_ventas_producto';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario',
        'estacion',        
        'producto',
        'compra',
        'venta',
        'venta_acum'
    ];
    
    protected $guarded = [
        
    ];
}
