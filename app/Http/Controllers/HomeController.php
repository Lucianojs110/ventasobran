<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use SisVentaNew\ArqueoPago;
use SisVentaNew\Articulo;
use SisVentaNew\Categoria;
use SisVentaNew\CuentaCorriente;
use SisVentaNew\Ingreso;
use SisVentaNew\Persona;
use SisVentaNew\Presupuesto;
use SisVentaNew\EstadisticasVentas;
use SisVentaNew\Config;
use SisVentaNew\Sucursal;
use  Carbon\Carbon;
use SisVentaNew\User;
use SisVentaNew\Venta;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function avisos()
    {
    
      
      $sucursal = Sucursal::where('id', session('id_sucursal'))->first();
      
      $aviso= Articulo::orderBy('stock', 'asc')->where('estado','Activo')->where('id_sucursal', session('sucursal'))->get();

      $estadistica = DB::table('detalle_venta as es')
       ->join('articulo as a','es.idarticulo','=','a.idarticulo')
       ->selectRaw('a.nombre, sum(es.cantidad) cantidad, sum(es.precio_venta) precio_venta')
       ->where('id_sucursal', session('sucursal'))
       ->groupBy('a.nombre')
       ->get();
       
      
     
      $end_date = Carbon::now();
      $start_date = $end_date ."- 2 week";

      $f1 = Carbon::parse($start_date);
      $f2 = Carbon::parse($end_date);  
    
      $promedioventa = DB::table('venta')
                    ->where("fecha_hora", ">=", $f1)
                    ->where("fecha_hora", "<=", $f2)
                    ->where('id_sucursal', session('sucursal'))
                    ->orderBy('created_at', 'asc')
                    ->get();

      $venta = Venta::where('id_sucursal', session('sucursal'))->get();
      
      $ingreso = Ingreso::where('id_sucursal', session('sucursal'))->get();
      
      $articulos = Articulo::where('estado','Activo')->where('id_sucursal', session('sucursal'))->get();
      
      $categorias = Categoria::where('id_sucursal', session('id_sucursal'))->get();
      
      $cliente = Persona::where('tipo_persona', 'Cliente')->where('id_sucursal', session('sucursal'))->get();
      
      $proveedor = Persona::where('tipo_persona', 'Proveedor')->where('id_sucursal', session('sucursal'))->get();
      
      $resultado = ArqueoPago::where('tipo_pago','Venta')->selectRaw('year(created_at) year, monthname(created_at) monthname, month(created_at) month, sum(pago_efectivo) efectivo, sum(pago_debito) debito, sum(pago_credito) credito, sum(pago_credito+pago_debito+pago_efectivo) data')
        ->groupBy('year', 'month', 'monthname')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->where('id_sucursal', session('sucursal'))
        ->get();


        $ganancias = ArqueoPago::selectRaw('year(created_at) year, monthname(created_at) monthname, month(created_at) month, sum(pago_efectivo) efectivo, sum(pago_debito) debito, sum(pago_credito) credito, sum(monto) data')
        ->groupBy('year', 'month', 'monthname')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->where('id_sucursal', session('sucursal'))
        ->get(); 




      $cuenta = CuentaCorriente::where('estado','Sin Cancelar')->where('id_sucursal', session('sucursal'))->get();
      $usuarios = User::where('estado','Activo')->get();


      return view('index' ,compact(
          'venta',
          'aviso',
          'estadistica',
          'promedioventa',
          'ingreso',
          'articulos',
          'categorias',
          'cliente',
          'proveedor',
          'resultado',
          'cuenta',
          'usuarios',
          'sucursal',
          'ganancias'

      ));
    }

}
