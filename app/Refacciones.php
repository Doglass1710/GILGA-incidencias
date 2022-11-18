<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refacciones extends Model
{
    protected $table = 'refacciones2';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
       
        'descripcion',  
        'prioridad',  
        'id_area_atencion',  
        'id_catalogo'   
                
    ];
    
    protected $guarded = [
        
    ];
}
