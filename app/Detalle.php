<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    public $timestamps = false;
    protected $table='Detalle';
    protected $primaryKey = 'Id_Detalle';
    protected $fillable = array('cantidad','subtotal','IdProducto','IdVenta');

   public function venta() { return $this->belongsTo('App\Venta','IdVenta');    }
    
   public function producto() {  return $this->hasOne('App\Producto','Id_Producto','IdProducto');  }

}
