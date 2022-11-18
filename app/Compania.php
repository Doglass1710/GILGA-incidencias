<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compania extends Model
{
    protected $table = 'companias';
    protected $pk = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id_usuario',        
        'razon_social',      
                
    ];
    
    protected $guarded = [
        
    ];
}
