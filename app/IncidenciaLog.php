<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidenciaLog extends Model
{
    protected $table = 'incidencias_logs';
    
    protected $fillable = [
        'id_usuario',        
        'id_incidencia',      
        'estatus',
        
    ];
    
    protected $guarded = [
        
    ];
}
