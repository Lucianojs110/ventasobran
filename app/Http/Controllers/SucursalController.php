<?php

namespace SisVentaNew\Http\Controllers;
use SisVentaNew\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class SucursalController extends Controller
{
    public function index(){
        $sucursales = Sucursal::where('estado','Activo')->get();
        
        return view('sucursal.index', compact(['sucursales']));
    }

    public function tabla()
    {
        $sucursales = Sucursal::where('estado','Activo')->get();

        return Datatables::of($sucursales)
          ->addColumn('opcion', function ($ar) {
              return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-borrar-' . $ar->id . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Borrar Sucursal: '.$ar->nombre.'"  class="fa fa-trash"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-editar-' . $ar->id . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar Sucursal: '.$ar->nombre.'"  class="fa fa-edit"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-show-' . $ar->id . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Sucursal: '.$ar->nombre.'"  class="fa fa-eye"></i></a>
                        </div>
                       ';
          })
          ->rawColumns(['opcion','imagen','nombre', 'estado','idcategoria'])
          ->make(true);
    }

    public function store(Request $request)
    {
        //valido si la nueva sucursal ya existe comparando el email 
        $vali = Sucursal::where('email', $request)->first();


        if($vali != null){
            toastr()->error('Error, sucursal(email) ya existente',);
            return Redirect::back();
        }
        if(auth()->user()->hasRole('Supervisor')){
            $suc = new Sucursal();
            $suc->nombre = $request->nombre;
            $suc->direccion = $request->direccion;
            $suc->ciudad = $request->ciudad;
            $suc->cuit = $request->cuit;
            $suc->ingresos_brutos = $request->ingresos_brutos;
            $suc->telefono = $request->telefono;
            $suc->email = $request->email;
            $suc->impuesto = $request->impuesto;

            $suc->save();
        }
        else{
            toastr()->error('No tiene permisos para realizar ésta acción',);
            return Redirect::back();
        }

        toastr()->success('Sucursal agregada correctamente!', ''.$request->name);
        return Redirect::back();
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->hasRole('Supervisor')){
            $suc = Sucursal::find($id);
            $suc->nombre = $request->nombre;
            $suc->direccion = $request->direccion;
            $suc->ciudad = $request->ciudad;
            $suc->cuit = $request->cuit;
            $suc->ingresos_brutos = $request->ingresos_brutos;
            $suc->telefono = $request->telefono;
            $suc->email = $request->email;
            $suc->impuesto = $request->impuesto;

            $suc->save();
            
            toastr()->info('Sucursal editada correctamente!', ''.$request->name);
            return Redirect::back();
        }else{
            toastr()->error('No tiene permisos para realizar tal accion',);
            return Redirect::back();
        }
        
    }

    public function delete($id)
    {
        if(auth()->user()->hasRole('Supervisor')){
            $suc = Sucursal::find($id);
            $suc->estado = 'Desactivo';
            $suc->save();

            toastr()->error('Sucursal eliminada correctamente!', ''.$suc->name);
            return Redirect::back();
        }else{
            toastr()->error('No tiene permisos para realizar tal accion',);
            return Redirect::back();
        }
    }
}
