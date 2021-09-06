<?php

namespace SisVentaNew;

use Illuminate\Database\Eloquent\Model;

class SucursalUser extends Model
{
    protected $table = 'sucursals_users';
    protected $primaryKey = 'id';

    protected $fillable=[
    	'user_id',
    	'sucursal_id',
    
    ];
}
