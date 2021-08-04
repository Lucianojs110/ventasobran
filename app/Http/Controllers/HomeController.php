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
    
      $aviso= Articulo::orderBy('stock', 'asc')->where('estado','Activo')->get();

      $estadistica = DB::table('estadistica_venta as es')
       ->join('articulo as a','es.idarticulo','=','a.idarticulo')
       ->selectRaw('a.nombre, sum(es.cantidad) cantidad, sum(es.precio_venta) precio_venta')
       ->groupBy('a.nombre')
       ->get();
       
      
     
      $end_date = Carbon::now();
      $start_date = $end_date ."- 2 week";

      $f1 = Carbon::parse($start_date);
      $f2 = Carbon::parse($end_date);  
    
      $promedioventa = DB::table('venta')
                    ->where("fecha_hora", ">=", $f1)
                    ->where("fecha_hora", "<=", $f2)
                    ->orderBy('created_at', 'asc')
                    ->get();

      $venta = Venta::all();
      
      $ingreso = Ingreso::all();
      
      $articulos = Articulo::where('estado','Activo')->where('estado','Activo')->get();
      
      $categorias = Categoria::All();
      
      $cliente = Persona::where('tipo_persona', 'Cliente')->get();
      
      $proveedor = Persona::where('tipo_persona', 'Proveedor')->get();
      
      $resultado = ArqueoPago::where('tipo_pago','Venta')->selectRaw('year(created_at) year, monthname(created_at) monthname, month(created_at) month, sum(pago_efectivo) efectivo, sum(pago_debito) debito, sum(pago_credito) credito, sum(pago_credito+pago_debito+pago_efectivo) data')
        ->groupBy('year', 'month', 'monthname')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        $ventas = ArqueoPago::where('tipo_pago','Venta')->selectRaw('year(created_at) year, monthname(created_at) monthname, month(created_at) month, sum(pago_efectivo) efectivo, sum(pago_debito) debito, sum(pago_credito) credito, sum(pago_credito+pago_debito+pago_efectivo) data')
        ->groupBy('year', 'month', 'monthname')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();


        $devolucion = ArqueoPago::where('tipo_pago','Devolucion')->selectRaw('year(created_at) year, monthname(created_at) monthname, month(created_at) month,  sum(monto) data')
        ->groupBy('year', 'month', 'monthname')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get(); 

      $cuenta = CuentaCorriente::where('estado','Sin Cancelar')->get();
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
          'ventas',
          'devolucion',
          'cuenta',
          'usuarios'

      ));
    }

}
