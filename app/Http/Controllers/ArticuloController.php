<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Support\Facades\URL;
use SisVentaNew\DetalleIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SisVentaNew\Articulo;
use SisVentaNew\Categoria;
use SisVentaNew\Config;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class ArticuloController extends Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function cambiar(Request $request, $id)
  {
      if ($request->precio_venta == null and $request->precio_compra == null)
      {
          toastr()->error('Tiene que llenar algun tipo de campo!', 'Atención');
          return Redirect::back();
      }

      $detalle = DetalleIngreso::where('idarticulo', $id)->orderBy('iddetalle_ingreso', 'desc')->first();
      $det = DetalleIngreso::find($detalle->iddetalle_ingreso);
      if ($request->precio_compra != null)
      {
          $det->precio_compra = $request->precio_compra;
      }
      if ($request->precio_venta != null)
      {
          $det->precio_venta = $request->precio_venta;
      }
      $det->save();

      toastr()->success('Sus precios se han editado correctamente!', 'Atención');
      return Redirect::back();

  }

  public function index(Request $request)
  {
      $cate = Config::all()->first();

      $categorias = Categoria::where('condicion',1)->get();

      $articulos = Articulo::with('categorias','detalleVentas','detalleIngresos', 'detalleDevoluciones')->where('estado','Activo')->get();


     return view('almacen.articulo.indexx', compact('articulos','categorias','cate'));
  }
  public function tabla()
  {
      $articulos = Articulo::with('categorias')->where('estado','Activo')->get();

      return Datatables::of($articulos)
          ->addColumn('opcion', function ($ar) {
              return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-borrar-' . $ar->idarticulo . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Borrar Artículo: '.$ar->nombre.'"  class="fa fa-trash"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-editar-' . $ar->idarticulo . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar Artículo: '.$ar->nombre.'"  class="fa fa-edit"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-show-' . $ar->idarticulo . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Artículo: '.$ar->nombre.'"  class="fa fa-eye"></i></a>
                        </div>
                       ';
          })
          ->editColumn('imagen', function ($art) {
              if ($art->imagen == "image.jpg")
              {
                  return 'No tiene imagen';
              }
              else
              {
                  return '<img class="center-block img-responsive" src="'.URL::to('/').'/imagenes/articulos/'.$art->imagen.'">';
              }
          })
          ->editColumn('idcategoria', function ($art) {
             return $art->categorias->nombre;
          })
          ->editColumn('nombre', function ($art) {
              return '<input name="idarticulo[]" form="pdfcodigo" id="label-'.$art->idarticulo.'" type="checkbox" value="'.$art->idarticulo.'"> - <label style="text-transform: uppercase" for="label-'.$art->idarticulo.'">'.$art->nombre.'</label>';
          })
          ->rawColumns(['opcion','imagen','nombre', 'estado','idcategoria'])
          ->make(true);
  }
  
  
  public function store(Request $request)
  {
      $articulo=new Articulo;
      $articulo->idcategoria=$request->idcategoria;
      if ($request->codigo == '' || $request->codigo == null) {
          srand((double) microtime( )*1000000);  //Introducimos la "semilla"
          $aleat = rand(1,999999999999);    //rand(mínimo,máximo);
          $articulo->codigo=$aleat;
      }else {
          $articulo->codigo=$request->codigo;
      }
      $articulo->nombre=$request->nombre;
      $articulo->stock= $request->stock;
      $articulo->precio_venta= $request->precio_venta;
      $articulo->descripcion=$request->descripcion;
      $articulo->estado='Activo';
      if ($request->file('imagen')) {
          $file= $request->file('imagen');
          $file->move(public_path().'/imagenes/articulos/', $file->getClientOriginalName());
          $articulo->imagen=$file->getClientOriginalName();
      }
      $articulo->save();


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


    $data = [
            'name' => $articulo->nombre,
            'type' => 'simple',
            'regular_price' => $articulo->precio_venta,
            'description' => '',
            'sku' => $articulo->codigo,
            'short_description' => $articulo->descripcion,
            'manage_stock' => 'true',
            'stock_quantity' => $articulo->stock,
            'status' => 'private',
           
           
        ];

       $result = $woocommerce->post('products', $data);
      
        } catch (\Exception $e) {

            toastr()->warning($e->getMessage().'','Su artículo no se pudo crear en WooCommerce');
        }
    }

          toastr()->success('Su artículo se ha agregado correctamente!', ''.$request->nombre);
        
      return Redirect::back();
  }
  
  public function show($id)
  {

    $articulodetallei = DB::table('detalle_ingreso as ingreso')
    ->where('ingreso.idarticulo', $id)
    ->get();
    $articulodetallev = DB::table('detalle_venta as venta')
    ->where('venta.idarticulo', $id)
    ->get();
    $articulodetallem = DB::table('detalle_mensual as mensual')
    ->where('mensual.idarticulo', $id)
    ->get();
    $todo= collect([$articulodetallei,$articulodetallev,$articulodetallem])->collapse();
    $todo2= $todo->sortBy(function($ordenar){
      return $ordenar->created_at;
    });
    $articulo=Articulo::findOrFail($id);
    //  dd($articulo->nombre);
    return view("almacen.articulo.show", compact('todo2','articulo'));
  }
  
  
  
  public function update(Request $request, $id)
  {

      if ( $request->precio_venta == null  and $request->precio_compra == null )
      {
          toastr()->error('Tiene que llenar precio venta o precio compra!', 'ATENCIÓN');
      }
      else
      {
          $detall = DetalleIngreso::where('idarticulo', $id)->orderBy('iddetalle_ingreso', 'desc')->first();

          if ($detall == null)
          {
              toastr()->error('No existe ningun INGRESO del producto '.$request->nombre.' por lo tanto no tiene precio de compra', 'Atención');
          }
          else
          {
              $detalle = DetalleIngreso::where('idarticulo', $id)->orderBy('iddetalle_ingreso', 'desc')->first();
              $det = DetalleIngreso::find($detalle->iddetalle_ingreso);
              if ($request->precio_compra != null)
              {
                  $det->precio_compra = $request->precio_compra;
              }
              if ($request->precio_venta != null)
              {
                  $det->precio_venta = $request->precio_venta;
              }
              $det->save();

              toastr()->success('El precio del producto '.$request->nombre.' se ha cambiado correctamente', 'ATENCIÓN');

          }

        
      }


      $articulo=Articulo::find($id);
      $articulo->idcategoria=$request->idcategoria;
      if ($request->codigo == '' || $request->codigo == null) {
          srand((double) microtime( )*1000000);  //Introducimos la "semilla"
          $aleat = rand(1,999999999999);    //rand(mínimo,máximo);
          $articulo->codigo=$aleat;
      }else {
          $articulo->codigo=$request->codigo;
      }
      $articulo->nombre=strtoupper($request->nombre);
      $articulo->stock= $request->stock;
      $articulo->precio_venta=$request->precio_venta; 
      $articulo->descripcion=$request->descripcion;
      if ($request->file('imagen')) {
          $file= $request->file('imagen');
          $file->move(public_path().'/imagenes/articulos/', $file->getClientOriginalName());
          $articulo->imagen=$file->getClientOriginalName();
      }
      $articulo->save();

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
        

           $data = [
            'regular_price' => $articulo->precio_venta,
            'stock_quantity' => $articulo->stock,
            'stock_quantity' => $articulo->stock,
            'name' => $articulo->nombre,
            'short_description' => $articulo->descripcion,
          ];
        
          $products = $woocommerce->get('products/?sku='. $articulo->codigo);

          foreach($products as $product){
              $id = $product->id;
          }

        $result = $woocommerce->put('products/'.$id, $data);

    } catch (\Exception $e) {

        toastr()->warning($e->getMessage().'','Su artículo no se pudo actualizar en WooCommerce');

      
      }
      
      
    }
  
          
    toastr()->info('Su artículo se ha editado correctamente!', ''.$request->nombre);

      return Redirect::back();


  }
  public function destroy($id)
    {
        $articulo=Articulo::find($id);
        $articulo->estado='Inactivo';
        $articulo->update();

        toastr()->error('Su artículo se ha borrado correctamente!', ''.$articulo->nombre);

        return Redirect::back();
    }

    public function actualizarprecios(Request $request)
    {
        
        if ($request->ajax()) {
            $idcategoria = $request->get('idcategoria'); 
            $accion = $request->get('accion'); 
            $porcentaje = $request->get('porcentaje');

            $articulo = DB::table('articulo')->where('idcategoria', $idcategoria)->get();

             foreach ($articulo as $articulos){

                    $data[] = [
                        'label' => $articulos->nombre,
                        'precio' => $articulos->precio_venta,
                        'stock' => $articulos->stock,
                        'codigo' => $articulos->codigo,
                        'idarticulo' => $articulos->idarticulo
                         ];

                    $id = $articulos->idarticulo;
                    $precio_anterior = $articulos->precio_venta;
                    $articulo=Articulo::find($id);
                     if ($accion=='1'){
                             $articulo->precio_venta = $precio_anterior*(($porcentaje/100)+1);
                          
                     }elseif($accion=='2'){
                         $articulo->precio_venta = $precio_anterior-(($precio_anterior*($porcentaje/100)));
                         
                         }
                    $precioventa = $articulo->precio_venta;
                    $articulo->save();


                    try {
                        $config1 = Config::where('idconfig','=',1)->get();
                        foreach($config1 as $config){
                            $url_API_woo = $config->url_API_woo;
                            $ck_API_woo = $config->ck_API_woo;
                            $cs_API_woo = $config->cs_API_woo;
    
                        }
                 
                        $woocommerce = new Client(
                        $url_API_woo,
                        $ck_API_woo,
                        $cs_API_woo,
                        ['version' => 'wc/v3']
                        );    
                        $products = $woocommerce->get('products/?sku='. $articulos->codigo);
                
                        foreach($products as $product){
                              $id = $product->id;
                          }
                          
                          $data = [
                            'regular_price' =>  number_format($precioventa, 2, '.', ''),
                          ];
            
                          $result = $woocommerce->put('products/'.$id, $data);
                    } catch (\Exception $e) {
                        toastr()->warning($e->getMessage().''.$precioventa.'','No se pudo actualizar el stock en WooCommerce');
                    }

            }        
     
       
            return ([$data, $accion]);
        }
    
    }

}
