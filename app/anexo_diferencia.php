<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class anexo_diferencia extends Model
{
    protected $table = 'anexo_diferencia';
    protected $pk = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario',
        'estacion',        
        'fecha',
        'turno',
        'diferencia_litros',
        'acumulado',
        'diferencia',
        'total'
    ];
    
    protected $guarded = [
        
    ];
}
