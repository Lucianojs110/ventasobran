<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use SisVentaNew\Gasto;
use SisVentaNew\TipoGasto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class GastosController extends Controller
{
    public function index(Request $request)
    {
        $tipogastos = TipoGasto::where('id_sucursal', session('sucursal'))
        ->where('activo',1)
        ->get();

        $gastos = Gasto::where('id_sucursal', session('sucursal'))
        ->get();

        $datenow = Carbon::now()->toDateString();
       
        return view('gastos.indexx', compact('tipogastos', 'datenow', 'gastos'));
    }

    public function tabla()
    {

        $gastos = Gasto::with('usuario', 'tipo_gasto')
        ->where('id_sucursal', session('sucursal'))
        ->get();

        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');

        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $gastos = Gasto::with('usuario', 'tipo_gasto')
                ->orderBy('id_gasto', 'desc')
                ->where("fecha", ">=", $f1)
                ->where("fecha", "<=", $f2)
                ->where('id_sucursal', session('sucursal'))
                ->get();

            $start_date = date('Y-m-d', strtotime($f1));
            $end_date = date('Y-m-d', strtotime($f2));
        }

       
        return Datatables::of($gastos)
            ->addColumn('opcion', function ($ga) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-borrar-gasto'. $ga->id_gasto . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Anular gasto: ' . $ga->id_gasto . '"  class="fa fa-trash"></i></a>
                           <a href="#" data-toggle="modal" data-target="#modal-editar-gasto' . $ga->id_gasto . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar  gasto"  class="fa fa-edit"></i></a>
                        </div>
                       ';
            })
            ->editColumn('fecha', function ($ga) {
                return date("d-m-Y", strtotime($ga->fecha));
            })
            ->editColumn('usuario', function ($ga) {
                return '<label for="' . $ga->usuario->name . '" style="text-transform: uppercase">' . $ga->usuario->apellido . ' ' .$ga->usuario->name. '</label>';
            })
            ->editColumn('gasto', function ($ga) {
                return $ga->tipo_gasto->gasto_nombre;
            })
            ->editColumn('importe', function ($ga) {
                return $ga->importe.' $';
            })
           
            ->rawColumns(['opcion', 'fecha', 'usuario', 'gasto', 'importe'])
            ->make(true);
    }




    public function tablatipo()
    {

        $tipogastos = TipoGasto::where('id_sucursal', session('sucursal'))
        ->where('activo',1)
        ->get();

     
        return Datatables::of($tipogastos)
            ->addColumn('opcion', function ($tiga) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-delete-' . $tiga->id_tipo_gasto . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Anular gasto: ' . $tiga->id_tipo_gasto . '"  class="fa fa-trash"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-editar-' . $tiga->id_tipo_gasto . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar tipo gasto"  class="fa fa-edit"></i></a>
                        </div>
                       ';
            })
            ->editColumn('nombre', function ($tiga) {
                return $tiga->gasto_nombre;
            })
            ->editColumn('importe', function ($tiga) {
                return $tiga->importe;
            })
           
         
           
            ->rawColumns(['opcion', 'fecha', 'usuario', 'gasto', 'importe'])
            ->make(true);
    }

    public function tabla_total(Request $request)
    {

        $start_date = (!empty(request('start_date'))) ? (request('start_date')) : ('');
        $end_date = (!empty(request('end_date'))) ? (request('end_date')) : ('');

        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $gastos = DB::table('gastos')
            ->select(DB::raw('sum(importe) as Total'))
            ->where("fecha", ">=", $f1)
            ->where("fecha", "<=", $f2)
            ->where('id_sucursal', session('sucursal'))
            ->where('deleted_at',  null)
            ->get();

           
        }else{

            $gastos = DB::table('gastos')
            ->select(DB::raw('sum(importe) as Total'))
            ->where('id_sucursal', session('sucursal'))
            ->where('deleted_at',  null)
            ->get();

        }
       
        return $gastos;
    }



    public function store(Request $request)
    {
    

        $gas = new Gasto();
        $gas->id_tipo_gasto = $request->tipo_gasto;
        $gas->importe= $request->importe;
        $gas->comprobante= $request->comprobante;
        $gas->id_usuario= Auth::user()->id;
        $gas->fecha = date('Y-m-d', strtotime($request->fecha));
        $gas->id_sucursal= session('sucursal');
        $gas->detalle= $request->detalle;
       
        $gas->save();
        
        toastr()->success('Gasto agregado correctamente!');
        return Redirect::back();
    }

    public function update(Request $request, $id)
    {
    

        $gas= Gasto::find($id);
        $gas->id_tipo_gasto = $request->tipo_gasto;
        $gas->importe= $request->importe;
        $gas->comprobante= $request->comprobante;
        $gas->id_usuario= Auth::user()->id;
        $gas->fecha = date('Y-m-d', strtotime($request->fecha));
        $gas->detalle= $request->detalle;
        $gas->save();


        toastr()->info('Su gasto fue editado correcatamente');

        return Redirect::back();
    }

    public function destroy($id)
    {
        $gas = Gasto::find($id);
       
        $gas->delete();

        toastr()->error('El gasto fue borrado correcatamente');

        return Redirect::back();
    }

    public function storetp(Request $request)
    {
    

        $tp = new TipoGasto();
        $tp->gasto_nombre = $request->tgnombre;
        $tp->importe= $request->tgimporte;
        $tp->id_sucursal= session('sucursal');
        $tp->activo = 1;
        $tp->save();
        
        toastr()->success('Tipo de gasto agregado correctamente!');
        return Redirect::back();
    }

    public function updatetg(Request $request, $id)
    {
    

        $tg= TipoGasto::find($id);
        $tg->gasto_nombre = $request->tgnombre;
        $tg->importe= $request->tgimporte;
        $tg->save();


        toastr()->info('Su tipo de gasto fue editada correcatamente', ''.  $request->gasto_nombre);

        return Redirect::back();
    }

    public function destroytg($id)
    {
        $tg = TipoGasto::find($id);
        $tg->activo = 0;
        $tg->save();

        toastr()->error('Su tipo de gasto fue borrada correcatamente', ''.  $tg->gasto_nombre);

        return Redirect::back();
    }


    public function consultagasto(Request $request)
    {
        $term = $request->get('idgasto');    
        if ($request->ajax()) {
        $gastostipo = DB::table('tipo_gasto')->where('id_tipo_gasto', $term )
        ->where('id_sucursal', session('sucursal'))
        ->get();

        foreach ($gastostipo as $gastostipos){

            $data[] = [

                'importe' => $gastostipos->importe
            
            ];
        }
        return $data;  
    }
       
    }

}
