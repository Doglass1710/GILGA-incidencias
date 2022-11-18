<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class incidencias_comentarios extends Model
{
    protected $table = 'incidencias_comentarios';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_incidencia',
        'comentario'
    ];
    
    protected $guarded = [
        
    ];
}
