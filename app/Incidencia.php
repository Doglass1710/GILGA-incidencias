<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';
    protected $pk = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id_usuario',        
        'folio',      
        'estacion',
        'fecha_incidencia',
        'id_area_estacion',
        'id_equipo',
        'asunto',
        'descripcion',
        'id_area_atencion',        
        'foto_ruta',
        'estatus_incidencia',
        'tipo_solicitud',
        'prioridad',
        'fecha_ultima_actualizacion',
        'fecha_cierre',
        'dias_vida_incidencia',
        'posicion',
        'cantidad',
        'id_refaccion',        
    ];
    
    protected $guarded = [
        
    ];
}
