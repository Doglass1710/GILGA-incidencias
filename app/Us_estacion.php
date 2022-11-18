<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Us_estacion extends Model
{
    protected $table = 'usuarios_estaciones';
    protected $pk = '';
    public $timestamps = false;
    
    protected $fillable = [       
        'id_usuario',                     
        'id_usuario_permiso',
        'estacion'
    ];
    
    protected $guarded = [
        
    ];
}
