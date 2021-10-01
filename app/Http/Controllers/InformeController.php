<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SisVentaNew\Sucursal;
use Carbon\Carbon;

class InformeController extends Controller
{
      public function index(Request $request)
    {
        $sucursal = Sucursal::where('estado','Activo')->get();
        return view('Informe.indexx', compact('sucursal'));
    }


    public function tabla(Request $request)
    {

       
        $start_date = (!empty(request('start_date'))) ? (request('start_date')) : ('');
        $end_date = (!empty(request('end_date'))) ? (request('end_date')) : ('');
         

        if(request('sucursal')=='0'){
        
        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $venta = DB::table('venta')
            ->select(DB::raw('sum(paga) as Efectivo, sum(tarjeta_debito) as Debito, sum(tarjeta_credito) as Credito, sum(total_venta) as Total'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('estado', 'Activa')
            ->get();

            $facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_fac'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('estado', 'Activa')
            ->where('cae', '!=', null)
            ->get();

            $no_facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_no_fac'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('estado', 'Activa')
            ->where('cae', '=', null)
            ->get();


            $ingreso = DB::table('ingreso')
            ->select(DB::raw('sum(total) as Total'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('estado', 'Sin cancelar')
            ->get();

            $gastos = DB::table('gastos')
            ->join('tipo_gasto', 'tipo_gasto.id_tipo_gasto', '=', 'gastos.id_tipo_gasto')
            ->select('tipo_gasto.gasto_nombre', 'gastos.id_tipo_gasto')
            ->selectRaw('SUM(gastos.importe) as importe')
            ->where("fecha", ">=", $f1)
            ->where("fecha", "<=", $f2)
            ->groupBy('gastos.id_tipo_gasto')
            ->get();

            $gasto_total = DB::table('gastos')
            ->selectRaw('SUM(gastos.importe) as gasto_total')
            ->where("fecha", ">=", $f1)
            ->where("fecha", "<=", $f2)
            ->get();

            $egreso_total = $gasto_total[0]->gasto_total + $ingreso[0]->Total;

         
 
        }else{
            
            $venta = DB::table('venta')
            ->select(DB::raw('sum(paga) as Efectivo, sum(tarjeta_debito) as Debito, sum(tarjeta_credito) as Credito, sum(total_venta) as Total'))
            ->where('estado', 'Activa')
            ->get();

            $facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_fac'))
            ->where('estado', 'Activa')
            ->where('cae', '!=', null)
            ->get();

            $no_facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_no_fac'))
            ->where('estado', 'Activa')
            ->where('cae', '=', null)
            ->get();

            $ingreso = DB::table('ingreso')
            ->select(DB::raw('sum(total) as Total'))
            ->where('estado', 'Sin cancelar')
            ->get();

            $gastos = DB::table('gastos')
            ->join('tipo_gasto', 'tipo_gasto.id_tipo_gasto', '=', 'gastos.id_tipo_gasto')
            ->select('tipo_gasto.gasto_nombre', 'gastos.id_tipo_gasto')
            ->selectRaw('SUM(gastos.importe) as importe')
            ->groupBy('gastos.id_tipo_gasto')
            ->get();

            $gasto_total = DB::table('gastos')
            ->selectRaw('SUM(gastos.importe) as gasto_total')
            ->get();

            $egreso_total = $gasto_total[0]->gasto_total + $ingreso[0]->Total;    
        }
        $data[] = [

            'venta' => $venta,
            'compra' => $ingreso,
            'gastos' => $gastos,
            'gasto_total' => $egreso_total,
            'facturado' => $facturado,
            'no_facturado' => $no_facturado
        ];

      }else{



        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $venta = DB::table('venta')
            ->select(DB::raw('sum(paga) as Efectivo, sum(tarjeta_debito) as Debito, sum(tarjeta_credito) as Credito, sum(total_venta) as Total'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('id_sucursal', request('sucursal'))
            ->where('estado', 'Activa')
            ->get();

            $facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_fac'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('estado', 'Activa')
            ->where('cae', '!=', null)
            ->where('id_sucursal', request('sucursal'))
            ->get();

            $no_facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_no_fac'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('estado', 'Activa')
            ->where('cae', '=', null)
            ->where('id_sucursal', request('sucursal'))
            ->get();



            $ingreso = DB::table('ingreso')
            ->select(DB::raw('sum(total) as Total'))
            ->where("fecha_hora", ">=", $f1)
            ->where("fecha_hora", "<=", $f2)
            ->where('id_sucursal', request('sucursal'))
            ->where('estado', 'Sin cancelar')
            ->get();

            $gastos = DB::table('gastos')
            ->join('tipo_gasto', 'tipo_gasto.id_tipo_gasto', '=', 'gastos.id_tipo_gasto')
            ->select('tipo_gasto.gasto_nombre', 'gastos.id_tipo_gasto')
            ->selectRaw('SUM(gastos.importe) as importe')
            ->where('gastos.id_sucursal', request('sucursal'))
            ->where("fecha", ">=", $f1)
            ->where("fecha", "<=", $f2)
            ->groupBy('gastos.id_tipo_gasto')
            ->get();

            $gasto_total = DB::table('gastos')
            ->selectRaw('SUM(gastos.importe) as gasto_total')
            ->where('gastos.id_sucursal', request('sucursal'))
            ->where("fecha", ">=", $f1)
            ->where("fecha", "<=", $f2)
            ->get();

            $egreso_total = $gasto_total[0]->gasto_total + $ingreso[0]->Total;

         
 
        }else{
            $venta = DB::table('venta')
            ->select(DB::raw('sum(paga) as Efectivo, sum(tarjeta_debito) as Debito, sum(tarjeta_credito) as Credito, sum(total_venta) as Total'))
            ->where('id_sucursal', request('sucursal'))
            ->where('estado', 'Activa')
            ->get();

            $facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_fac'))
            ->where('estado', 'Activa')
            ->where('cae', '!=', null)
            ->where('id_sucursal', request('sucursal'))
            ->get();

            $no_facturado = DB::table('venta')
            ->select(DB::raw('sum(total_venta) as total_no_fac'))
            ->where('estado', 'Activa')
            ->where('cae', '=', null)
            ->where('id_sucursal', request('sucursal'))
            ->get();

            $ingreso = DB::table('ingreso')
            ->select(DB::raw('sum(total) as Total'))
            ->where('id_sucursal', request('sucursal'))
            ->where('estado', 'Sin cancelar')
            ->get();

            $gastos = DB::table('gastos')
            ->join('tipo_gasto', 'tipo_gasto.id_tipo_gasto', '=', 'gastos.id_tipo_gasto')
            ->select('tipo_gasto.gasto_nombre', 'gastos.id_tipo_gasto')
            ->selectRaw('SUM(gastos.importe) as importe')
            ->where('gastos.id_sucursal', request('sucursal'))
            ->groupBy('gastos.id_tipo_gasto')
            ->get();

            $gasto_total = DB::table('gastos')
            ->selectRaw('SUM(gastos.importe) as gasto_total')
            ->where('gastos.id_sucursal', request('sucursal'))
            ->get();

            $egreso_total = $gasto_total[0]->gasto_total + $ingreso[0]->Total;    
        }
       
        $data[] = [

            'venta' => $venta,
            'compra' => $ingreso,
            'gastos' => $gastos,
            'gasto_total' => $egreso_total,
            'facturado' => $facturado,
            'no_facturado' => $no_facturado
        ];



      }

      
        
        return $data;
    }
}
