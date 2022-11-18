<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario_equipos';
    protected $pk = 'id_inventario';
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',        
        'id_inventario',      
        'id_estacion',
        'sitio',
        'equipo',
        'cantidad',
        'marca',
        'modelo',
        'serie',        
        'estado',
        'observaciones'
    ];
    protected $guarded = [
        
    ];
}
