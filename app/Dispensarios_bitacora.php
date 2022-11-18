<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispensarios_bitacora extends Model{

protected $table="dispensarios_bitacora";
protected $pk="id";
public $timestamps=false;

protected $fillable=[
    'estacion',
    'fecha',
    'hora'
];

protected $guarded=[];

}