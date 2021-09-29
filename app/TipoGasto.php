<?php

namespace SisVentaNew;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoGasto extends Model
{
 
    protected $table='tipo_gasto';

    protected $primaryKey='id_tipo_gasto';
}
