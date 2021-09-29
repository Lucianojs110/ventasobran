<?php

namespace SisVentaNew;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use SoftDeletes;
    protected $table='gastos';
    protected $primaryKey='id_gasto';
    

    public function tipo_gasto()
    {
        return $this->hasOne('SisVentaNew\TipoGasto', 'id_tipo_gasto', 'id_tipo_gasto');
    }

    public function usuario()
    {
        return $this->hasOne('SisVentaNew\User',  'id', 'id_usuario');
    }
}
