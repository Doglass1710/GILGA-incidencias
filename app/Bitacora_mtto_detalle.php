<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora_mtto_detalle extends Model
{
    protected $table = 'bitacora_mtto_detalle';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'id_bitacora',
        'cantidad',
        'unidad',
        'refaccion',
        'importe',
        'iva',
        'total'
    ];
    
    protected $guarded = [
        
    ];
}
