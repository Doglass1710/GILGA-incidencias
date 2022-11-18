<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora_mtto extends Model
{
    protected $table = 'bitacora_mtto';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'id_vehiculo',
        'fecha_bitacora',
        'nota',
        'trabajo',
        'observaciones',
        'id_usuario'
    ];
    
    protected $guarded = [
        
    ];
}
