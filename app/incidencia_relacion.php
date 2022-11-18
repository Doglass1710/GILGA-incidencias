<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class incidencia_relacion extends Model
{
    protected $table = 'incidencias_relacion';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'id_incidencia',
        'id_requerimiento',
        'id_usuario'      
    ];
    
    protected $guarded = [
        
    ];
}
