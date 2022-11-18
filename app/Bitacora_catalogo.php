<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora_Catalogo extends Model
{
    protected $table = 'bitacora_catalogo';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'descripcion',
        'id_usuario'
    ];
    
    protected $guarded = [
        
    ];
}
