<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public $timestamps = false;
    protected $table='Venta';
    protected $primaryKey = 'Id_Venta';
    protected $fillable = array('fecha','iva','descuento','total','IdCliente');

    public function cliente() { 
        return $this->hasOne('App\Cliente','Id_Cliente','IdCliente');
    }

    public function detalle(){
        return $this->hasMany('App\Detalle','IdVenta');
    }
}
