<?php

namespace SisVentaNew\Http\Controllers;

use SisVentaNew\Arqueo;
use SisVentaNew\ArqueoDetalle;
use SisVentaNew\ArqueoPago;
use SisVentaNew\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SisVentaNew\Http\Requests\IngresoFormRequest;
use SisVentaNew\Ingreso;
use SisVentaNew\Persona;
use SisVentaNew\DetalleIngreso;
use SisVentaNew\Config;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use Response;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;
use SisVentaNew\Sucursal;
use Illuminate\Support\Facades\DB;


class IngresoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        $ingreso = Ingreso::with('detalles', 'proveedor')->where('id_sucursal', session('sucursal'))->get();

        return view('compras.ingreso.index', compact('ingreso'));

    }

    public function tabla()
    {

        $ingreso = Ingreso::with('detalles', 'proveedor')->where('id_sucursal', session('sucursal'))->get();

        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');

        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $ingreso = Ingreso::with('detalles', 'proveedor')
                ->orderBy('idingreso', 'desc')
                ->where("fecha_hora", ">=", $f1)
                ->where("fecha_hora", "<=", $f2)
                ->where('id_sucursal', session('sucursal'))
                ->get();

            $start_date = date('Y-m-d', strtotime($f1));
            $end_date = date('Y-m-d', strtotime($f2));
        }

       
        return Datatables::of($ingreso)
            ->addColumn('opcion', function ($ar) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-delete-' . $ar->idingreso . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Anular Ingreso: ' . $ar->num_comprobante . '"  class="fa fa-trash"></i></a>
                          <a href="' . route('ingreso.show', $ar->idingreso) . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Ingreso: ' . $ar->num_comprobante . '"  class="fa fa-eye"></i></a>
                        </div>
                       ';
            })
            ->editColumn('fecha', function ($art) {
                return date("d-m-Y", strtotime($art->fecha_hora));
            })
            ->editColumn('proveedor', function ($art) {
                return '<label for="' . $art->proveedor->nombre . '" style="text-transform: uppercase">' . $art->proveedor->nombre . '</label>';
            })
            ->editColumn('comprobante', function ($art) {
                return $art->tipo_comprobante . ': '.$art->num_comprobante ;
            })
            ->editColumn('total_ingreso', function ($art) {
                return $art->total.' $';
            })
            ->editColumn('estado', function ($art) {
                if ($art->estado != "Cancelada") {
                    return '<span class="label label-danger">' . $art->estado . '</span>';
                } else {
                    return '<span class="label label-info">' . $art->estado . '</span>';
                }
            })
            ->rawColumns(['opcion', 'proveedor', 'fecha', 'comprobante', 'estado'])
            ->make(true);
    }


    public function tabla_total(Request $request)
    {

       
        $start_date = (!empty(request('start_date'))) ? (request('start_date')) : ('');
        $end_date = (!empty(request('end_date'))) ? (request('end_date')) : ('');

        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $ingreso = DB::table('ingreso')
            ->select(DB::raw('sum(total) as Total'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('id_sucursal', session('sucursal'))
            ->where('estado', 'Sin cancelar')
            ->get();

           
        }else{

            $ingreso = DB::table('ingreso')
            ->select(DB::raw('sum(total) as Total'))
            ->where('id_sucursal', session('sucursal'))
            ->where('estado', 'Sin cancelar')
            ->get();

        }
       
        return $ingreso;
    }

    public function create()
    {
        $personas = Persona::where('tipo_persona', 'Proveedor')->where('id_sucursal', session('sucursal'))->get();
        $articulos = Articulo::where('id_sucursal', session('sucursal'));
        $ing = Ingreso::all()->last();
        if ($ing == null) {
            $ing = '1';
            return view("compras.ingreso.create", compact('personas','articulos','ing'));
        } else {
            $ing = Ingreso::all()->last();
            return view("compras.ingreso.create", compact('personas','articulos','ing'));
        }
    }

    public function store(IngresoFormRequest $request)
    {

          

            $ingreso = new Ingreso;
            $ingreso->idproveedor = $request->get('idproveedor');
            $ingreso->tipo_comprobante = $request->get('tipo_comprobante');
            $ingreso->num_comprobante = $request->get('num_comprobante');
            $ingreso->total = $request->get('total_ingreso');

            $mytime = Carbon::now('America/Argentina/Mendoza');
            $ingreso->fecha_hora = $mytime->toDateTimeString();
            $ingreso->impuesto = '21';
            $ingreso->estado = 'Sin cancelar';
            $ingreso->id_sucursal = session('sucursal');
            $ingreso->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_compra = $request->get('precio_compra');
            $precio_venta = $request->get('precio_venta');
            $codigo = $request->get('codigo');

            $cont = 0;

            while ($cont < count($idarticulo)) {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_compra = $precio_compra[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $id = $idarticulo[$cont];
                $articulo= Articulo::findOrFail($id);
                $articulo->precio_venta = $precio_venta[$cont];
                $articulo->update();


                $config1 = Sucursal::where('id',session('sucursal'))->get();
                foreach($config1 as $config){
                    $url_API_woo = $config->url_API_woo;
                    $ck_API_woo = $config->ck_API_woo;
                    $cs_API_woo = $config->cs_API_woo;
                    $sincronizar = $config->sincronizar;
                }
          
                if($sincronizar=='1'){
                
                try {
               
             
                    $woocommerce = new Client(
                    $url_API_woo,
                    $ck_API_woo,
                    $cs_API_woo,
                    ['version' => 'wc/v3']
                    );    
                    $products = $woocommerce->get('products/?sku='. $codigo[$cont]);
            
                    foreach($products as $product){
                          $id = $product->id;
                          $wccantidad = $product->stock_quantity;
                      }
                      $cantidad1 = $wccantidad + $cantidad[$cont];
                      $data = [
                        'stock_quantity' => $cantidad1,
                        'regular_price' => $precio_venta[$cont]
                      ];
        
                      $result = $woocommerce->put('products/'.$id, $data);
                } catch (\Exception $e) {
                    toastr()->warning($e->getMessage().'','No se pudo actualizar el stock en WooCommerce');
                }
            }

                $cont = $cont + 1;
            }

            $ing = DB::table('ingreso as i')
                ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
                ->select(DB::raw('sum(di.cantidad*precio_compra) as total'))
                ->where('i.idingreso', $ingreso->idingreso)
                ->first();

           
         

        $ing = Ingreso::all()->last();
        $pro = Persona::findOrFail($ing->idproveedor);
        toastr()->success('Su ingreso se ha creado correctamente!', 'Atención');
        return Redirect::back();
    }

    public function show($id)
    {

        $ingreso = Ingreso::with('detalles.articulo', 'proveedor')->where('idingreso',$id)->first();
        return view("compras.ingreso.show", compact('ingreso'));
    }

    public function destroy($id)
    {
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->Estado = 'Cancelada';
        $ingreso->update();
        toastr()->error('Su ingreso se ha cencelado correctamente!', 'Atención');

        return Redirect::back();
    }
}
