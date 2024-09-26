<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public $timestamps = false;
    protected $table='Producto';
    protected $primaryKey = 'Id_Producto';
    protected $fillable = array('nombre','precio','cantidad');
}
