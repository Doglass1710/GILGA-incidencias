<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class anexo_ventas extends Model
{
    protected $table = 'anexo_ventas';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario',
        'estacion',        
        'producto',
        'compra',
        'venta'
    ];
    
    protected $guarded = [
        
    ];
}
