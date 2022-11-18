<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIncidencia extends Model
{
    protected $table = 'detalle_incidencias';
    protected $pk = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id_incidencia',        
        'id_usuario',      
        'fecha_detalle_incidencia',
        'comentarios',
        'foto_ruta',
        'estatus',                
    ];
    
    protected $guarded = [
        
    ];
}
