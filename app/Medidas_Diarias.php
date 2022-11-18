<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medidas_Diarias extends Model
{
    protected $table = 'medidas_diarias';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario',
        'estacion',        
        'magna',
        'magna_T2',
        'premium',
        'diesel',
        'pipa_magna',
        'pipa_premium',
        'pipa_diesel',
        'observ_magna',
        'observ_premium',
        'observ_diesel'
    ];
    
    protected $guarded = [
        
    ];
}
