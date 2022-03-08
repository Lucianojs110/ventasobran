<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use SisVentaNew\CuentaCorriente;
use SisVentaNew\CuentaCorrienteDetalle;
use SisVentaNew\Devolucion;
use SisVentaNew\Http\Requests\VentasFormRequest;
use SisVentaNew\Venta;
use SisVentaNew\Config;
use SisVentaNew\ArqueoPago;
use SisVentaNew\DetalleVenta;
use SisVentaNew\Presupuesto;
use SisVentaNew\Persona;
use SisVentaNew\Sucursal;
use SisVentaNew\EstadisticasVentas;
use Carbon\Carbon;
use SisVentaNew\Arqueo;
use SisVentaNew\ArqueoDetalle;
use SisVentaNew\Articulo;
use Yajra\DataTables\Facades\DataTables;
use Automattic\WooCommerce\Client;
use Afip;



class VentaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $ventas = Venta::with('detalles', 'cliente')->where('id_sucursal', session('sucursal'))->orderBy('idventa', 'desc')->get();
        return view('ventas.venta.indexx', compact('ventas'));
    }

    public function create()
    {
        
        $config2 = Config::where('idconfig','=',1)->get();
        $personas = Persona::where('tipo_persona', 'Cliente')->where('id_sucursal', session('sucursal'))->get();

        $articulos = Articulo::with('detalleIngresos')
            ->where('estado', 'Activo')
            ->where('stock', '>', '0')
            ->where('id_sucursal', session('sucursal'))
            ->select(DB::raw('CONCAT(articulo.codigo, " ",articulo.nombre) AS articulo'), 'articulo.idarticulo', 'articulo.stock',
                DB::raw('(SELECT precio_venta From detalle_ingreso Where idarticulo = articulo.idarticulo order by iddetalle_ingreso desc limit 0,1)
                 as precio_promedio'))
            ->get();

//        dd($articulos);
        $mytime = Carbon::now('America/Argentina/Mendoza');
        $Date = date("d-m-Y H:i:s", strtotime($mytime));

        $ven = Venta::all()->last();
        if ($ven == null) {
            $ven = '1';
            return view("ventas.venta.create", compact('personas', 'articulos', 'ven', 'config2', 'Date'));
        } else {
            return view("ventas.venta.create", compact('personas', 'articulos', 'ven', 'config2', 'Date'));
        }
    }

    public function tabla()
    {

        $ventas = Venta::with('detalles', 'cliente')
        ->where('id_sucursal', session('sucursal'))
        ->orderBy('idventa', 'desc')->get();

        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');

        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $ventas = Venta::with('detalles', 'cliente')
                ->orderBy('idventa', 'desc')
                ->where("fecha_hora", ">=", $f1)
                ->where("fecha_hora", "<=", $f2)
                ->where('id_sucursal', session('sucursal'))
                ->get();


            $start_date = date('Y-m-d', strtotime($f1));
            $end_date = date('Y-m-d', strtotime($f2));
        }

        return Datatables::of($ventas)
            ->addColumn('opcion', function ($ar) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-delete-' . $ar->idventa . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Anular Venta: ' . $ar->num_comprobante . '"  class="fa fa-trash"></i></a>
                          <a href="' . route('venta.show', $ar->idventa) . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Venta: ' . $ar->num_comprobante . '"  class="fa fa-eye"></i></a>
                        </div>
                       ';
            })
            ->editColumn('fecha', function ($art) {
                return date("d-m-Y", strtotime($art->fecha_hora));
            })
            ->editColumn('cliente', function ($art) {
                return '<label for="' . $art->cliente->nombre . '" style="text-transform: uppercase">' . $art->cliente->nombre . '</label>';
            })
            ->editColumn('comprobante', function ($art) {
                return '<a href="' . route('venta.ticke', $art->idventa) . '">' . $art->tipo_comprobante . ': '.$art->num_comprobante. '</a>';
            })
            ->editColumn('total_venta', function ($art) {
                return $art->total_venta . ' $';
            })
            ->editColumn('estado', function ($art) {
                if ($art->estado == "Cancelada") {
                    return '<span class="label label-warning">' . $art->estado . '</span>';
                } else if ($art->estado == "Anulada") {
                    return '<span class="label label-danger">' . $art->estado . '</span>';
                } else
                {
                    return '<span class="label label-info">' . $art->estado . '</span>';
                }
            })
           
            ->rawColumns(['opcion', 'cliente', 'fecha', 'comprobante', 'estado', 'total_venta'])
            ->make(true);
    }

    public function tabla_total(Request $request)
    {

       
        $start_date = (!empty(request('start_date'))) ? (request('start_date')) : ('');
        $end_date = (!empty(request('end_date'))) ? (request('end_date')) : ('');

        
        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $venta = DB::table('venta')
            ->select(DB::raw('sum(paga) as Efectivo, sum(tarjeta_debito) as Debito, sum(tarjeta_credito) as Credito ,sum(total_venta) as Total'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('id_sucursal', session('sucursal'))
            ->where('estado', '!=','Anulada')
            ->get();

           
        }else{

            $venta = DB::table('venta')
            ->select(DB::raw('sum(paga) as Efectivo, sum(tarjeta_debito) as Debito, sum(tarjeta_credito) as Credito ,sum(total_venta) as Total'))
            ->where('id_sucursal', session('sucursal'))
            ->where('estado', '!=','Anulada')
            ->get();

        }
        

       
        return $venta;
    }
    
    
    
    public function store(VentasFormRequest $request)
    {

        $val = Arqueo::where('estado', 'Abierto')->where('id_sucursal',session('sucursal'))->first();
        if ($val == null) {
            toastr()->error('Debe iniciar un arqueo, antes de realizar una venta!', 'Atención');
            return Redirect::back();
        }

        $fecha =Venta::orderBy('idventa', 'desc')->first();
        $mytime = Carbon::now('America/Argentina/Mendoza');
        $ventaact = $mytime->toDateString();


        $Date = date("d-m-Y H:i:s", strtotime($mytime));

        $config3 = Config::where('idconfig','=',1)->get();
            foreach($config3 as $config){
                $nombreTienda = $config->nombre;
                $lemaTienda = $config->lema;
             
            }

    
        if ($request->tipo_venta == 'Cuenta Corriente')
        {

            $corriente = CuentaCorriente::where('idcliente', $request->idcliente2)->where('estado','Sin Cancelar')->first();
            $ultimo = Venta::orderBy('idventa', 'desc')->where('id_sucursal', session('sucursal'))->first();
            $ultimo2 = CuentaCorriente::orderBy('idcuenta_corriente', 'desc')->first();
            if ($corriente == null)
            {
                $to = $request->get('total_des') + $request->get('monto_porcentaje');
                $venta = new CuentaCorriente();
                $venta->idcliente = $request->get('idcliente2');
                $venta->id_sucursal = session('sucursal');
                $venta->tipo_comprobante = $request->get('tipo_comprobante');
                $venta->num_comprobante =   $ultimo->idventa;
                $venta->monto_porcentaje = $request->get('monto_porcentaje');
                $venta->porcentaje_credito = $request->get('porcentaje_credito');
                $venta->porcentaje_descuento = $request->get('descuento_total');
                $venta->total_venta = $to;
                $venta->paga = $request->paga;
                $venta->tarjeta_debito = $request->tarjeta_debito;
                $venta->tarjeta_credito = $request->tarjeta_credito;

                $mytime = Carbon::now('America/Argentina/Mendoza');
                $venta->fecha_hora = $mytime->toDateTimeString();
                $venta->impuesto = '21';
                $venta->estado = 'Sin Cancelar';
                $venta->save();

                $idarticulo = $request->get('idarticulo');
                $cantidad = $request->get('cantidad');
                $descuento = $request->get('descuento');
                $precio_venta = $request->get('precio_venta');
                $codigo = $request->get('codigo');


                if ($request->paga != 0) {
                    $pago = 'Efectivo';
                }
                if ($request->tarjeta_debito != 0) {
                    $pago = 'Tarjeta de Debito';
                }
                if ($request->tarjeta_credito != 0) {
                    $pago = 'Tarjeta de Credito';
                }

                $arqueo = Arqueo::where('estado', 'Abierto')->where('id_sucursal',session('sucursal'))->first();

              
                $ar = Arqueo::find($arqueo->idarqueo);
                $ar->total_dia = $arqueo->total_dia + $to;
                $ar->save();
               
                $pago = New ArqueoPago();
                $pago->id_sucursal = session('sucursal');
                $pago->idarqueo = $arqueo->idarqueo;
                $pago->idventa = 0;
                $pago->idingreso = 0;
                $pago->tipo_pago = 'Venta en Cuenta Corriente'; 
                $pago->pago_efectivo = 0;
                $pago->pago_debito = 0;
                $pago->pago_credito = 0;
                $pago->monto = $to;
                $pago->save();

                $cont = 0;

                while ($cont < count($idarticulo)) {
                    $detalle = new CuentaCorrienteDetalle();
                    $detalle->idcuenta_corriente = $venta->idcuenta_corriente;
                    $detalle->idarticulo = $idarticulo[$cont];
                    $detalle->cantidad = $cantidad[$cont];
                    $detalle->descuento = $descuento[$cont];
                    $detalle->precio_venta = $precio_venta[$cont];
                    $detalle->save();


                    $estVenta = new EstadisticasVentas();
                    $estVenta->idarticulo = $idarticulo[$cont];
                    $estVenta->cantidad = $cantidad[$cont];
                    $estVenta->precio_venta = $precio_venta[$cont];
                    $estVenta->save();

                    $ar = Articulo::find($idarticulo[$cont]);

                    
                    $arde = New ArqueoDetalle();
                    $arde->idarqueo = $arqueo->idarqueo;
                    $arde->monto = $precio_venta[$cont];
                    $arde->cantidad = $cantidad[$cont];
                    $arde->tipo_venta = 'Cuenta Corriente';
                    $arde->tipo_pago = $pago->tipo_pago;
                    $arde->descripcion = 'Se Vendio: ' . $ar->nombre;
                    $arde->total = $cantidad[$cont] * $precio_venta[$cont];
                    $arde->save();
                    


                    $config1 = Sucursal::where('id',session('sucursal'))->get();
                    foreach($config1 as $config){
                        $url_API_woo = $config->url_API_woo;
                        $ck_API_woo = $config->ck_API_woo;
                        $cs_API_woo = $config->cs_API_woo;
                        $sincronizar = $config->sincronizar;
                    }
              
                    if($sincronizar=='1' && $request->get('canal_venta')=='local'){
                    
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
                          $cantidad1 = $wccantidad - $cantidad[$cont];
                          $data = [
                            'stock_quantity' => $cantidad1
                          ];
            
                          $result = $woocommerce->put('products/'.$id, $data);
                    } catch (\Exception $e) {
                        toastr()->warning($e->getMessage().'','No se pudo actualizar el stock en WooCommerce');
                    }
                }

                    $cont = $cont + 1;
                }
            }
            else
            {
                $corriente = CuentaCorriente::where('idcliente', $request->idcliente)->where('estado','Sin Cancelar')->first();

                $to = $request->get('total_des') + $request->get('monto_porcentaje');

                $corrienteCorriente = CuentaCorriente::find($corriente->idcuenta_corriente);
                $corrienteCorriente->total_venta = $to + $corriente->total_venta;
                $corrienteCorriente->paga = $request->paga + $corriente->paga;
                $corrienteCorriente->tarjeta_debito = $request->tarjeta_debito + $corriente->tarjeta_debito;
                $corrienteCorriente->tarjeta_credito = $request->tarjeta_credito + $corriente->tarjeta_credito;
                $corrienteCorriente->save();

                $idarticulo = $request->get('idarticulo');
                $cantidad = $request->get('cantidad');
                $descuento = $request->get('descuento');
                $precio_venta = $request->get('precio_venta');
                $codigo = $request->get('codigo');


                if ($request->paga != 0) {
                    $pago = 'Efectivo';
                }
                if ($request->tarjeta_debito != 0) {
                    $pago = 'Tarjeta de Debito';
                }
                if ($request->tarjeta_credito != 0) {
                    $pago = 'Tarjeta de Credito';
                }

                $arqueo = Arqueo::where('estado', 'Abierto')->where('id_sucursal',session('sucursal'))->first();

          
            
                $pago = New ArqueoPago();
                $pago->id_sucursal = session('sucursal');
                $pago->idarqueo = $arqueo->idarqueo;
                $pago->idventa = 0;
                $pago->idingreso = 0;
                $pago->tipo_pago = 'Venta en Cuenta Corriente'; 
                $pago->pago_efectivo = 0;
                $pago->pago_debito = 0;
                $pago->pago_credito = 0;
                $pago->monto = $to;
                $pago->save();

                $cont = 0;

                while ($cont < count($idarticulo)) {
                    $detalle = new CuentaCorrienteDetalle();
                    $detalle->idcuenta_corriente = $corriente->idcuenta_corriente;
                    $detalle->idarticulo = $idarticulo[$cont];
                    $detalle->cantidad = $cantidad[$cont];
                    $detalle->descuento = $descuento[$cont];
                    $detalle->precio_venta = $precio_venta[$cont];
                    $detalle->save();

                    $estVenta = new EstadisticasVentas();
                    $estVenta->idarticulo = $idarticulo[$cont];
                    $estVenta->cantidad = $cantidad[$cont];
                    $estVenta->precio_venta = $precio_venta[$cont];
                    $estVenta->save();

                    $ar = Articulo::find($idarticulo[$cont]);

                    
                    $arde = New ArqueoDetalle();
                    $arde->idarqueo = $arqueo->idarqueo;
                    $arde->monto = $precio_venta[$cont];
                    $arde->cantidad = $cantidad[$cont];
                    $arde->tipo_venta = 'Cuenta Corriente';
                    $arde->tipo_pago = $pago->tipo_pago;
                    $arde->descripcion = 'Se Vendio: ' . $ar->nombre;
                    $arde->total = $cantidad[$cont] * $precio_venta[$cont];
                    $arde->save(); 


                    $config1 = Sucursal::where('id',session('sucursal'))->get();
                    foreach($config1 as $config){
                        $url_API_woo = $config->url_API_woo;
                        $ck_API_woo = $config->ck_API_woo;
                        $cs_API_woo = $config->cs_API_woo;
                        $sincronizar = $config->sincronizar;
                    }
              
                    if($sincronizar=='1' && $request->get('canal_venta')=='local'){
                    
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
                          $cantidad1 = $wccantidad - $cantidad[$cont];
                          $data = [
                            'stock_quantity' => $cantidad1
                          ];
            
                          $result = $woocommerce->put('products/'.$id, $data);
                    } catch (\Exception $e) {
                        toastr()->warning($e->getMessage().'','No se pudo actualizar el stock en WooCommerce');
                    }

                }
                    $cont = $cont + 1;

                    
                }
            }
        }

        else
        {

            $to = $request->get('total_des') + $request->get('monto_porcentaje');
            $venta = new Venta;
            $venta->idcliente = $request->get('idcliente2');
            $venta->id_sucursal = session('sucursal'); 
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->monto_porcentaje = $request->get('monto_porcentaje');
            $venta->porcentaje_credito = $request->get('porcentaje_credito');
            $venta->porcentaje_descuento = $request->get('descuento_total');
            $venta->total_venta = $to;
            $venta->idusuario = $request->get('idusuario');
            $venta->paga = $request->paga;
           
            if($request->tarjeta_debito==null){
                $venta->tarjeta_debito=0;
            }else{
                $venta->tarjeta_debito = $request->tarjeta_debito;
            }
            if($request->tarjeta_credito==null){
                $venta->tarjeta_credito=0;
            }else{
                $venta->tarjeta_credito = $request->tarjeta_credito;
            }
            
           

            $mytime = Carbon::now('America/Argentina/Mendoza');
            $venta->fecha_hora = $mytime->toDateTimeString();
            $venta->impuesto = '21';
            $venta->estado = 'Activa';
            $venta->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');
            $codigo = $request->get('codigo');
            $articulo = $request->get('articulo');


            if ($request->paga != 0) {
                $pago = 'Efectivo';
            }
            if ($request->tarjeta_debito != 0) {
                $pago = 'Tarjeta de Debito';
            }
            if ($request->tarjeta_credito != 0) {
                $pago = 'Tarjeta de Credito';
            }

            $arqueo = Arqueo::where('estado', 'Abierto')->where('id_sucursal',session('sucursal'))->first();

            
            $ar = Arqueo::find($arqueo->idarqueo);
            $ar->total_dia = $arqueo->total_dia + $to;
            $ar->save();
            

            $pago = New ArqueoPago();
            $pago->id_sucursal = session('sucursal');
            $pago->idarqueo = $arqueo->idarqueo;
            $pago->idventa = $venta->idventa;
            $pago->idingreso = 0;
            $pago->tipo_pago = 'Venta';

            if ($request->tarjeta_debito == 0 and $request->tarjeta_credito == 0 and $request->paga != 0) {
//                efectivo
                $pago->pago_efectivo = $request->get('total_des'); 
                $pago->pago_debito = 0;
                $pago->pago_credito = 0;

            } elseif ($request->paga == 0) {
//                debito y credito
                $pago->pago_efectivo = $request->paga;
                if($request->tarjeta_debito==null){
                    $pago->pago_debito=0;
                }else{
                    $pago->pago_debito = $request->tarjeta_debito;
                }
                $pago->pago_credito = $request->tarjeta_credito + $request->get('monto_porcentaje');

            } elseif ($request->paga != 0 and $request->tarjeta_credito != 0) {
//                credito y efectivo

                $total = (($request->tarjeta_credito + ($request->paga - $request->get('total_des'))) - $request->paga) * -1;

                $pago->pago_efectivo = $total;
                if($request->tarjeta_debito==null){
                    $pago->pago_debito=0;
                }else{
                    $pago->pago_debito = $request->tarjeta_debito;
                }
                $pago->pago_credito = $request->tarjeta_credito + $request->get('monto_porcentaje');

            } elseif ($request->paga != 0 and $request->tarjeta_debito != 0) {
//                debito y efectivo

                $total = (($request->tarjeta_debito + ($request->paga - $request->get('total_des'))) - $request->paga) * -1;

                $pago->pago_efectivo = $total;
                if($request->tarjeta_debito==null){
                    $pago->pago_debito=0;
                }else{
                    $pago->pago_debito = $request->tarjeta_debito;
                }
                $pago->pago_credito = $request->tarjeta_credito + $request->get('monto_porcentaje');

            }
            $pago->monto = $to;
            $pago->save();

            $cont = 0;

            while ($cont < count($idarticulo)) {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->idventa;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->descuento = $descuento[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $sCantidad = $cantidad[$cont];
                $sPrecioVenta = $precio_venta[$cont];
                $stotal = $sCantidad * $sPrecioVenta;

               
                $stotal = 0;
               

                $estVenta = new EstadisticasVentas();
                $estVenta->idarticulo = $idarticulo[$cont];
                $estVenta->cantidad = $cantidad[$cont];
                $estVenta->precio_venta = $precio_venta[$cont];
                $estVenta->save();

        
                $ar = Articulo::find($idarticulo[$cont]);

                $arde = New ArqueoDetalle();
                $arde->idarqueo = $arqueo->idarqueo;
                $arde->monto = $precio_venta[$cont];
                $arde->cantidad = $cantidad[$cont];
                $arde->tipo_venta = 'Venta';
                $arde->tipo_pago = $pago->tipo_pago;
                $arde->descripcion = 'Se Vendio: ' . $ar->nombre;
                $arde->total = $cantidad[$cont] * $precio_venta[$cont];
                $arde->save();

                $config1 = Sucursal::where('id',session('sucursal'))->get();
                foreach($config1 as $config){
                    $url_API_woo = $config->url_API_woo;
                    $ck_API_woo = $config->ck_API_woo;
                    $cs_API_woo = $config->cs_API_woo;
                    $sincronizar = $config->sincronizar;
                }
          
                if($sincronizar=='1' && $request->get('canal_venta')=='local'){
                
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
                  $cantidad1 = $wccantidad - $cantidad[$cont];
                  $data = [
                    'stock_quantity' => $cantidad1
                  ];
    
                  $result = $woocommerce->put('products/'.$id, $data);
            } catch (\Exception $e) {
                toastr()->warning($e->getMessage().'','No se pudo actualizar el stock en WooCommerce');
            }
        }

                $cont = $cont + 1;
            }
        }
       

        toastr()->success('Su venta se ha creado correctamente!', 'Atención');
        return Redirect::back();
    }

    public function show($id)
    {
        $venta = Venta::with('detalles.articulo', 'cliente', 'usuario')->where('idventa', $id)->orderBy('idventa', 'desc')->first();
        $devo = Devolucion::all();

        return view("ventas.venta.show", compact('venta', 'devo'));

    }

    public function ticke($id)
    {
        $venta = Venta::with('detalles.articulo', 'cliente', 'usuario')->where('idventa', $id)->orderBy('idventa', 'desc')->first();
       
        
        
        $venta1=DB::table('venta')->where('idventa', $id)->get();

        foreach ($venta1 as $venta2)
        {
         $tipo_comprobante = $venta2->tipo_comprobante;
         $cae = $venta2->cae;
         $vtocae1 = $venta2->vtocae;
        }

        $vtocae = str_replace("-","",$vtocae1);

        $sucursal = Sucursal::where('id',session('sucursal'))->first();
        //dd($sucursal );

        

            $cuit = $sucursal->cuit;
            $punto_venta = $sucursal->punto_venta;
       

        if($tipo_comprobante=='Factura C'){
            $tipo_comp = '011';
        }elseif($tipo_comprobante=='Factura A'){
            $tipo_comp = '001';
        }else{
            $tipo_comp = '006';
        }

        
        $codigo = $cuit.$tipo_comp.str_pad($punto_venta, 5, "0", STR_PAD_LEFT).$cae.$vtocae;

        return view("ventas.venta.tickes", compact('venta', 'codigo', 'sucursal'));

    }


 

    public function destroy($id)
    {
        $venta = Venta::find($id);
        $venta->estado = 'Anulada';
        $venta->save();
        toastr()->error('Su venta se ha anulado correctamente!', 'Atención');
        return Redirect::back();
    }


    public function consultacodigo(Request $request)
    {
        
        $codigoart = request('codigoart');    
        if ($request->ajax()) {
            $articulo = DB::table('articulo')->where('codigo','like', '%'. $codigoart. '%')->where('id_sucursal', session('sucursal'))->get()
            ->where('estado', 'Activo');
            return (["articulo"=>$articulo]);    
        return (["articulo"=>$articulo]);    
            return (["articulo"=>$articulo]);    
        return (["articulo"=>$articulo]);    
            return (["articulo"=>$articulo]);    
        }
    }   

    public function consultaproducto(Request $request)
    {
        
        $term = $request->get('term');    
        if ($request->ajax()) {
        $articulo = DB::table('articulo')->where('nombre', 'LIKE', '%'. $term. '%')
        ->where('estado', 'Activo')
        ->where('id_sucursal', session('sucursal'))
        ->get();

        foreach ($articulo as $articulos){

            $data[] = [

                'label' => $articulos->nombre,
                'precio' => $articulos->precio_venta,
                'stock' => $articulos->stock,
                'codigo' => $articulos->codigo,
                'idarticulo' => $articulos->idarticulo
            ];
        }

        
        return $data;
          
    }
       
    }


    public function consultafactura(Request $request, $id)
    {
        
        $config=DB::table('config')->where('idconfig','=','1')->get();

        foreach ($config as $config1)
        {
         $cuit = $config1->dni;
         $punto_venta = $config1->punto_venta; 
        }

        $afip = new Afip(array('CUIT' => $cuit));
        $voucher_info = $afip->ElectronicBilling->GetVoucherInfo($id,2,11); //Devuelve la información del comprobante 1 para el punto de venta 1 y el tipo de comprobante 6 (Factura B)

        if($voucher_info === NULL){
           echo 'El comprobante no existe';
        }
        else{
           echo 'Esta es la información del comprobante:';
           echo '<pre>';
           print_r($voucher_info);
           echo '</pre>';
        }
        //dd($voucher_info);
       

    }

    public function solicitarcae(Request $request)
    {
    
        $idventa = request('idventa'); 
        $venta=DB::table('venta')->where('idventa','=', $idventa)
        ->join('persona','venta.idcliente', '=','persona.idpersona')
        ->get(); 

        foreach ($venta as $venta1)
        {
         $idcliente = $venta1->idpersona; 
         $doc_tipo = $venta1->tipo_documento;
         $doc_cliente = $venta1->num_documento; 
         $ImpTotal = $venta1->total_venta;
         $tipo_comprobante = $venta1->tipo_comprobante;
        }

        $sucursales=Sucursal::where('id', session('sucursal'))->get();

        foreach ($sucursales as $sucursal)
        {
         $cuit = $sucursal->cuit;
         $punto_venta = $sucursal->punto_venta; 
        }

        if ($idcliente==1){
            $doctipo=99;
        }else if($doc_tipo=='DNI'){
                $doctipo=96;
            }else if ($doc_tipo=='CUIT'){
                $doctipo=80;
            }   
        

        if ($request->ajax()) {

        if($tipo_comprobante=='Factura C'){

        $afip = new Afip(array('CUIT' => $cuit));
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_venta ,11);
        $numComp = $last_voucher + 1;

        
        $ImpNeto = $ImpTotal/1.21;
        $ImpNeto = number_format((float)$ImpNeto, 2, '.', '');
        $ImpIVA = $ImpTotal - $ImpNeto;
        $ImpIVA = number_format((float)$ImpIVA, 2, '.', '');
        
        $date = Carbon::now('America/Argentina/Buenos_Aires');
        $date2 = $date->format('Ymd');
        $dateqr = $date->format('Y-m-d');

        $data = array(
            'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> $punto_venta,  // Punto de venta
            'CbteTipo' 	=> 11,  // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 	=> $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
            'DocNro' 	=> intval($doc_cliente),  // Número de documento del comprador (0 consumidor final)
            'CbteDesde' 	=> $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
            'CbteFch' 		=> intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 	=> $ImpTotal, // Importe total del comprobante
            'ImpTotConc' 	=> 0,   // Importe neto no gravado
            'ImpNeto' 	=> $ImpTotal, // Importe neto gravado
            'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> 0,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            
        );
        
        $res = $afip->ElectronicBilling->CreateVoucher($data);
        
        $cae=$res['CAE']; //CAE asignado el comprobante
        $vtocae = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)

        $venta= venta::find($idventa);
        $venta->cae = $cae;
        $venta->vtocae = $vtocae;
        $num_fac = $last_voucher + 1;
        $venta->num_comprobante = str_pad($punto_venta, 4, "0", STR_PAD_LEFT).'-'.str_pad($num_fac, 8, "0", STR_PAD_LEFT);
        $venta->fecha_hora = $dateqr;
        $data = '{"ver":1,"fecha":'.$dateqr.',"cuit":'.$cuit.',"ptoVta":'.$punto_venta.',"tipoCmp":11,"nroCmp":'.$num_fac.',"importe":'.$ImpTotal.',"moneda":"PES","ctz":1,"tipoDocRec":'.$doctipo.',"nroDocRec":'.$doc_cliente.',"tipoCodAut":"E","codAut":'.$cae.'}';
        $data64 = "https://www.afip.gob.ar/fe/qr/?p=".base64_encode($data);
        $venta->codigoQr = $data64;
        
        $venta->save();
        return (["res"=>$res]);
        }
        
      } else if ($tipo_comprobante == "Factura B"){

        $afip = new Afip(array('CUIT' => $cuit));
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_venta,6);
        $numComp = $last_voucher + 1;

        $ImpTotal = request('total_venta');
        $ImpNeto = $ImpTotal/1.21;
        $ImpNeto = number_format((float)$ImpNeto, 2, '.', '');
        $ImpIVA = $ImpTotal - $ImpNeto;
        $ImpIVA = number_format((float)$ImpIVA, 2, '.', '');
        
        $date = Carbon::now('America/Argentina/Buenos_Aires');
        $date2 = $date->format('Ymd');
        

        $data = array(
            'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> $punto_venta,  // Punto de venta
            'CbteTipo' 	=> 6,  // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 	=> $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
            'DocNro' 	=> intval($cuit),  // Número de documento del comprador (0 consumidor final)
            'CbteDesde' 	=> $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
            'CbteFch' 		=> intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 	=> $ImpTotal, // Importe total del comprobante
            'ImpTotConc' 	=> 0,   // Importe neto no gravado
            'ImpNeto' 	=> $ImpNeto, // Importe neto gravado
            'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> $ImpIVA,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                    'BaseImp' 	=> $ImpNeto, // Base imponible
                    'Importe' 	=> $ImpIVA // Importe 
                )
            ), 
        );
        
        $res = $afip->ElectronicBilling->CreateVoucher($data);
        
        $cae=$res['CAE']; //CAE asignado el comprobante
        $vtocae = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)

        $venta= venta::find($idventa);
        $venta->cae = $cae;
        $venta->vtocae = $vtocae;
        $num_fac = $last_voucher + 1;
        $venta->num_comprobante = str_pad($punto_venta, 4, "0", STR_PAD_LEFT).'-'.str_pad($num_fac, 8, "0", STR_PAD_LEFT);
        $venta->save();
        return (["res"=>$res]);


       }else if ($tipo_comprobante == "Factura A"){
       
    
        $afip = new Afip(array('CUIT' => $cuit));
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_venta,1);
        $numComp = $last_voucher + 1;

        $ImpTotal = request('total_venta');
        $ImpNeto = $ImpTotal/1.21;
        $ImpNeto = number_format((float)$ImpNeto, 2, '.', '');
        $ImpIVA = $ImpTotal - $ImpNeto;
        $ImpIVA = number_format((float)$ImpIVA, 2, '.', '');
        
        $date = Carbon::now('America/Argentina/Buenos_Aires');
        $date2 = $date->format('Ymd');
        $clientCuit = request('cuit');
        
        $data = array(
            'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> intval($punto),  // Punto de venta
            'CbteTipo' 	=> 1,  // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 	=> 80, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
            'DocNro' 	=> intval($clientCuit),  // Número de documento del comprador (0 consumidor final)
            'CbteDesde' 	=> $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
            'CbteFch' 		=> intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 	=> $ImpTotal, // Importe total del comprobante
            'ImpTotConc' 	=> 0,   // Importe neto no gravado
            'ImpNeto' 	=> $ImpNeto, // Importe neto gravado
            'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> $ImpIVA,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                    'BaseImp' 	=> $ImpNeto, // Base imponible
                    'Importe' 	=> $ImpIVA // Importe 
                )
            ), 
        );
        
        $res = $afip->ElectronicBilling->CreateVoucher($data);
        
        $cae=$res['CAE']; //CAE asignado el comprobante
        $vtocae = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd) 

        $venta= venta::find($idventa);
        $venta->cae = $cae;
        $venta->vtocae = $vtocae;
        $num_fac = $last_voucher + 1;
        $venta->num_comprobante = str_pad($punto_venta, 4, "0", STR_PAD_LEFT).'-'.str_pad($num_fac, 8, "0", STR_PAD_LEFT);
        $venta->save();
        return (["res"=>$res]);

    }

  }
}
