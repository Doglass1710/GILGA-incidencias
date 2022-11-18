<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    protected $table = 'estaciones';
    protected $pk = 'estacion';
    public $timestamps = true;
    
    protected $fillable = [
        'id_usuario',            
        'estacion',       
        'id_compania',
        'nombre_corto',
        'direccion',
        'permiso_expedido',
        'reg_patronal',        
    ];
    
    protected $guarded = [
        
    ];
}
