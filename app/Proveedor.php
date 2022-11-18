<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $pk = 'proveedor';
    public $timestamps = false;
    
    protected $fillable = [
       
        'razon_social',      
                
    ];
    
    protected $guarded = [
        
    ];
}
