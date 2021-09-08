<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SisVentaNew\Http\Requests\PersonaFormRequest;
use SisVentaNew\Persona;
use Yajra\DataTables\Facades\DataTables;
use SisVentaNew\Config;
use Afip;

class ClienteController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $personas = Persona::with('ventas.detalles')->where('tipo_persona', 'Cliente')->where('id_sucursal', session('sucursal'))->get();


        return view('ventas.cliente.indexx', compact('personas'));
    }

    public function tabla()
    {
        $personas = Persona::with('ventas')->where('tipo_persona', 'Cliente')->where('id_sucursal', session('sucursal'))->get();

        return Datatables::of($personas)
            ->addColumn('opcion', function ($ar) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-borrar-' . $ar->idpersona . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Borrar Cliente: ' . $ar->nombre . '"  class="fa fa-trash"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-editar-' . $ar->idpersona . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar Cliente: ' . $ar->nombre . '"  class="fa fa-edit"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-show-' . $ar->idpersona . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Cliente: ' . $ar->nombre . '"  class="fa fa-eye"></i></a>
                        </div>
                       ';
            })
            ->editColumn('nombre', function ($art) {
                return '<label style="text-transform: uppercase">' . $art->nombre . '</label>';
            })
            ->editColumn('documento', function ($art) {
                return $art->tipo_documento . ' - ' . $art->num_documento;
            })
            ->rawColumns(['opcion', 'nombre'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $persona = new Persona;
        $persona->tipo_persona = 'Cliente';
        $persona->id_sucursal = session('sucursal');
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request->get('num_documento');
        $persona->direccion = $request->get('direccion');
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->tipo_contribuyente = $request->get('tipo');
        $persona->save();


        toastr()->success('Su cliente se ha agregado correctamente!', '' . $request->nombre);

        return Redirect::back();
    }

    public function update(PersonaFormRequest $request, $id)
    {
        // dd($request);
        $persona = Persona::find($id);
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request->get('num_documento');
        $persona->direccion = $request->get('direccion');
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->tipo_contribuyente = $request->get('tipo');
        $persona->save();

        toastr()->info('Su cliente se ha editado correctamente!', '' . $request->nombre);

        return Redirect::back();
    }

    public function destroy($id)
    {
        $persona = Persona::find($id);
        $persona->tipo_persona = 'Inactivo';
        $persona->save();

        toastr()->error('Su cliente se ha borrado correctamente!', '' . $persona->nombre);
        return Redirect::back();
    }

    public function consultarcuit(Request $request)
    {

        if ($request->ajax()) {

            $config1 = Config::where('idconfig','=',1)->get();
            foreach($config1 as $config){
            $cuitnegocio = $config->dni;
            }

        $cuit = request('num_documento');
        $afip = new Afip(array('CUIT' => $cuitnegocio));
        $persona = $afip->RegisterScopeFive->GetTaxpayerDetails($cuit);
        $server_status =  $afip->RegisterScopeFive->GetServerStatus();
        
        return response()->json([
            'persona' => $persona,
            'estatus' => $server_status
        ]);
        }
    }

}
