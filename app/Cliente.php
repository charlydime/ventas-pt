<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $timestamps = false;
    protected $table='Cliente';
    protected $primaryKey = 'Id_Cliente';
    protected $fillable = array('nombre','Estado');
   
    //protected $hidden = ['created_at','updated_at'];

}
