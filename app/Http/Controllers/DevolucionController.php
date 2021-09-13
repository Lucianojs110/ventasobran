<?php

namespace SisVentaNew\Http\Controllers;

use SisVentaNew\Articulo;
use SisVentaNew\Devolucion;
use SisVentaNew\DevolucionDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;use Yajra\DataTables\Facades\DataTables;
use SisVentaNew\Config;
use Automattic\WooCommerce\Client;
use SisVentaNew\Arqueo;
use SisVentaNew\ArqueoPago;
use SisVentaNew\Venta;
use SisVentaNew\ArqueoDetalle;

class DevolucionController extends Controller
{
    public function store(Request $request)
    {
        $arqueo = Arqueo::where('estado', 'Abierto')->where('id_sucursal', session('sucursal'))->first();
        if ($arqueo != null) {

        $vali = DB::table('devolucions')->where('idventa', $request->idventa)->first();

        if ($vali != null) {
            toastr()->error('Ya Existe una devolución!', 'Atención');
            return Redirect::back();
        }

        $venta= venta::find($request->idventa);
        $venta->estado = 'Anulada';
        $venta->save();

        $deco = new Devolucion();
        $deco->num_factura = $request->num_factura;
        $deco->id_sucursal = session('sucursal');
        $deco->idventa = $request->idventa;
        $deco->idcliente = $request->idcliente;
        $mytime = Carbon::now('America/Argentina/Mendoza');
        $deco->fecha_devolucion = $mytime->toDateTimeString();
        $deco->num_comprobante = $request->num_comprobante;
        $deco->save();

        $idarticulo = $request->get('idarticulo');
        $cantidad = $request->get('cantidad');
        $sube_resta = $request->get('suma_resta');
        $descripcion = $request->get('descripcion');


        $venta = Venta::where('idventa','=',$request->idventa)->get();
        foreach($venta as $ventas){
            $total_venta = $ventas->total_venta;
            $efectivo = $ventas->paga;
            $credito = $ventas->tarjeta_credito;
            $credito_interes = $ventas->monto_porcentaje;
            $debito = $ventas->tarjeta_debito;
            $idventa = $ventas->idventa;
        }
        
       
              
        $ar = Arqueo::find($arqueo->idarqueo);
        $ar->total_dia = $arqueo->total_dia - $total_venta;
        $ar->save();
       
        $pago = New ArqueoPago();
        $pago->idarqueo = $arqueo->idarqueo;
        $pago->id_sucursal = session('sucursal');
        $pago->idventa = $idventa;
        $pago->idingreso = 0;
        $pago->tipo_pago = 'Devolucion'; 
        $pago->pago_efectivo = -1*$efectivo;
        $pago->pago_debito = -1*$debito;
        $pago->pago_credito = -1*($credito+$credito_interes);
        $pago->monto = -1*$total_venta;
        $pago->save();

        $arde = New ArqueoDetalle();
        $arde->idarqueo = $arqueo->idarqueo;
        $arde->monto = $total_venta;
        $arde->cantidad = 1;
        $arde->tipo_venta = 'Devolucion';
        $arde->tipo_pago = $pago;
        $arde->descripcion = 'Devolucion de productos';
        $arde->total = $total_venta;
        $arde->save();



        $cont = 0;

        while ($cont < count($idarticulo)) {
            $detalle = new DevolucionDetalle();
            $detalle->iddevolucion = $deco->iddevolucion;
            $detalle->idarticulo = $idarticulo[$cont];
            if ($sube_resta[$cont] == 'sumar') {
                $arti = Articulo::find($idarticulo[$cont]);
                $arti->stock = $arti->stock + $cantidad[$cont];
                $arti->save();
            }
            $detalle->cantidad = $cantidad[$cont];
            $detalle->descripcion = $descripcion[$cont];
            $detalle->sube_resta = $sube_resta[$cont];
            $detalle->save();

            $articulo = Articulo::where('idarticulo','=',$idarticulo[$cont])->get();
            foreach($articulo as $art){
                $codigo = $art->codigo;
            }


            $config1 = Config::where('idconfig','=',1)->get();
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
                $products = $woocommerce->get('products/?sku='. $codigo);
        
                foreach($products as $product){
                      $id = $product->id;
                      $wccantidad = $product->stock_quantity;
                  }
                  $cantidad1 = $wccantidad + $cantidad[$cont];
                  $data = [
                    'stock_quantity' => $cantidad1,
                  ];
    
                  $result = $woocommerce->put('products/'.$id, $data);
            } catch (\Exception $e) {
                toastr()->warning($e->getMessage().'','No se pudo actualizar el stock en WooCommerce');
            }
        }

            $cont = $cont + 1;
        }

        toastr()->success('Se ha devuelto el o los productos!', 'Atención');

        return Redirect::route('devolucion.show', $deco->iddevolucion);
    }else{

        toastr()->error('Debe iniciar un arqueo, antes de hacer una devolucion!', 'Atención');
            return Redirect::back();
    }
    }

    public function index(Request $request)
    {

        $devolucion = Devolucion::with('personas')->where('id_sucursal',session('sucursal'))->get();

        return view('ventas.devolucion.inicio', compact('devolucion'));

    }

    public function tabla()
    {

        $devolucion = Devolucion::with('personas')->where('id_sucursal',session('sucursal'))->get();

        return Datatables::of($devolucion)
            ->addColumn('opcion', function ($ar) {
                return '
                        <div class="btn-group">
                          <a href="' . route('devolucion.show', $ar->iddevolucion) . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Devolución: ' . $ar->num_comprobante . '"  class="fa fa-eye"></i></a>
                        </div>
                       ';
            })
            ->editColumn('fecha', function ($art) {
                return date("d-m-Y", strtotime($art->fecha_devolucion));
            })
           
            ->rawColumns(['opcion', 'cliente', 'fecha'])
            ->make(true);
    }

    public function show($id)
    {
//        dd($id);
        $devolucion = DB::table('devolucions as d')
           
            ->where('d.iddevolucion', '=', $id)
            ->first();
        //dd($venta);
        $detalles = DB::table('devolucion_detalles as dd')
            ->join('articulo as a', 'dd.idarticulo', '=', 'a.idarticulo')
            ->where('dd.iddevolucion', '=', $id)
            ->select('dd.*', 'a.nombre')
            ->get();
//        dd($detalles);

        return view('ventas.devolucion.show', compact('detalles', 'devolucion'));
    }

    public function pdf($id)
    {
        $devolucion = DB::table('devolucions as d')
         
            ->where('d.iddevolucion', '=', $id)
            ->first();
        //dd($venta);
        $detalles = DB::table('devolucion_detalles as dd')
            ->join('articulo as a', 'dd.idarticulo', '=', 'a.idarticulo')
            ->where('dd.iddevolucion', '=', $id)
            ->select('dd.*', 'a.nombre')
            ->get();

        $pdf = \PDF::loadView('ventas.devolucion.pdf', compact('detalles', 'devolucion'));

//        $pdf = \PDF::loadView('pdf.estimacion' , array('estimacion'=>$estimacion, 'config'=>$config, 'detalles'=>$detalles ,'usuario'=> $usuario));

        return $pdf->stream('Detalle de la Devolución:' . $devolucion->fecha_devolucion . '.pdf');

    }
}
