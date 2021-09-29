<?php


Route::get('borrar-base', ['uses' => 'ConfigController@borrar']);

Route::get('/offline', function() {
    return view('vendor/laravelpwa/offline'); });

Route::get('plantillas/master', ['as' => 'plantilla', 'uses' => 'ConfigController@plantilla']);


Route::group(['middleware' => 'auth'], function () {

//    PDF
    Route::post('/pdf-codigo', 'PDFController@codigoarticulo')->name('pdf.codigo');
    Route::get('/pdf-cliente', 'PDFController@cliente')->name('pdf.cliente');
    Route::get('/pdf-proveedor', 'PDFController@proveedor')->name('pdf.proveedor');
    Route::get('pdf-categoria', 'PDFController@categoria')->name('pdf.categoria');
    Route::get('pdf-presupuesto/{id}', 'PDFController@presupuesto')->name('pdf.presupuesto');
    Route::get('pdf-ingreos/{id}', 'PDFController@ingreso')->name('pdf.ingreso');
    Route::get('pdf-estimacion/{id}', 'PDFController@estimacion')->name('pdf.estimacion');
    Route::get('pdf-mensual/{id}', 'PDFController@mensual')->name('pdf.mensual');
    Route::get('pdf-venta/{id}', 'PDFController@venta')->name('pdf.venta');
    Route::get('pdf-facturaa/{id}', 'VentaController@facturaa')->name('pdf.facturaa');
    Route::get('pdf-facturab/{id}', 'VentaController@facturab')->name('pdf.facturab');
    Route::get('devolucion-pdf/{id}', 'DevolucionController@pdf')->name('devolucion.pdf');
    Route::get('pdf/arqueo', 'PDFController@arqueo')->name('pdf.arqueo');
    Route::get('pdf-corriente/{id}', 'PDFController@corriente')->name('pdf.corriente');


//    ARTICULO
    Route::get('/articulo', 'ArticuloController@index')->name('articulo.index');
    Route::get('/articulo-tabla', 'ArticuloController@tabla')->name('articulo.tabla');
    Route::post('articulo-store', 'ArticuloController@store')->name('articulo.store');
    Route::patch('articulo-update/{id}', 'ArticuloController@update')->name('articulo.update');
    Route::delete('articulo-delete/{id}', 'ArticuloController@destroy')->name('articulo.destroy');
    Route::put('articulo-cambiar/{id}', 'ArticuloController@cambiar')->name('cambiar.precio');
    Route::post('actualizarprecios', 'ArticuloController@actualizarprecios')->name('actualizarprecios');


//    CATEGORIA
    Route::get('/categoria', 'CategoriaController@index')->name('categoria.index')->middleware('supervendedor');
    Route::get('/categoria-tabla', 'CategoriaController@tabla')->name('categoria.tabla')->middleware('supervendedor');
    Route::post('categoria-store', 'CategoriaController@store')->name('categoria.store')->middleware('supervendedor');
    Route::patch('categoria-update/{id}', 'CategoriaController@update')->name('categoria.update')->middleware('supervendedor');
    Route::delete('categoria-delete/{id}', 'CategoriaController@destroy')->name('categoria.destroy')->middleware('supervendedor');


//    CLIENTES
    Route::get('/cliente', 'ClienteController@index')->name('cliente.index')->middleware('supervendedor');
    Route::get('/cliente-tabla', 'ClienteController@tabla')->name('cliente.tabla')->middleware('supervendedor');
    Route::post('cliente-store', 'ClienteController@store')->name('cliente.store')->middleware('superv.admin');
    Route::patch('cliente-update/{id}', 'ClienteController@update')->name('cliente.update')->middleware('superv.admin');
    Route::delete('cliente-delete/{id}', 'ClienteController@destroy')->name('cliente.destroy')->middleware('superv.admin');
    Route::post('consultarcuit', 'ClienteController@consultarcuit')->name('consultarcuit')->middleware('superv.admin');

//    PROVEEDORES
    Route::get('/proveedor', 'ProveedorController@index')->name('proveedor.index');
    Route::get('/proveedor-tabla', 'ProveedorController@tabla')->name('proveedor.tabla');
    Route::post('proveedor-store', 'ProveedorController@store')->name('proveedor.store');
    Route::patch('proveedor-update/{id}', 'ProveedorController@update')->name('proveedor.update');
    Route::delete('proveedor-delete/{id}', 'ProveedorController@destroy')->name('proveedor.destroy');

//    VENTA
    Route::get('ventas', 'VentaController@index')->name('venta.index')->middleware('supervendedor');
    Route::get('ventas-crear', 'VentaController@create')->name('venta.create')->middleware('supervendedor');
    Route::get('ventas-tabla', 'VentaController@tabla')->name('venta.tabla')->middleware('supervendedor');
    Route::get('ventas-ver/{id}', 'VentaController@show')->name('venta.show')->middleware('supervendedor');
    Route::get('ventas-editar/{id}', 'VentaController@edit')->name('venta.edit')->middleware('supervendedor');
    Route::get('venta-ticke/{id}', 'VentaController@ticke')->name('venta.ticke')->middleware('supervendedor');
    Route::post('ventas-store', 'VentaController@store')->name('venta.store')->middleware('supervendedor');
    Route::get('ventas-ver/{id}', 'VentaController@show')->name('venta.show')->middleware('supervendedor');
    Route::delete('ventas-borrar/{id}',  'VentaController@destroy')->name('venta.destroy')->middleware('superv.admin');
    Route::patch('ventas-update/{id}', 'VentaController@update')->name('venta.update')->middleware('superv.admin');
    Route::post('/solicitarcae', 'VentaController@solicitarcae')->name('solicitarcae')->middleware('supervendedor');
    Route::post('/consultacodigo', 'VentaController@consultacodigo')->name('consultacodigo')->middleware('supervendedor');
    Route::get('/consultaproducto', 'VentaController@consultaproducto')->name('consultaproducto')->middleware('supervendedor');
    Route::get('/consultafactura', 'VentaController@consultafactura')->name('consultafactura')->middleware('supervendedor');
    Route::get('/tablatotal', 'VentaController@tabla_total')->name('tablatotal');


//    INGRESO
    Route::get('ingreso', 'IngresoController@index' )->name('ingreso.index');
    Route::get('ingreso-crear', 'IngresoController@create' )->name('ingreso.create');
    Route::get('ingreso-tabla', 'IngresoController@tabla'  )->name('ingreso.tabla');
    Route::get('ingreso-ver/{id}','IngresoController@show'  )->name('ingreso.show');
    Route::get('ingreso-editar/{id}','IngresoController@edit'  )->name('ingreso.edit');
    Route::post('ingreso-store', 'IngresoController@store' )->name('ingreso.store');
    Route::patch('ingreso-update/{id}','IngresoController@update'  )->name('ingreso.update');
    Route::delete('ingreso-borrar/{id}', 'IngresoController@destroy' )->name('ingreso.destroy');
    Route::get('/tablatotalingreso', 'IngresoController@tabla_total')->name('tablatotalingreso');


//    DEVOLUCION
    Route::get('devolucion-inicio', 'DevolucionController@index')->name('devolucion.index')->middleware('supervendedor');
    Route::get('devolucion-tabla', 'DevolucionController@tabla')->name('devolucion.tabla')->middleware('supervendedor');
    Route::get('devolucion-ver/{id}', 'DevolucionController@show')->name('devolucion.show')->middleware('supervendedor');
    Route::post('devolucion/store', 'DevolucionController@store')->name('devolucion.store')->middleware('supervendedor');


//    ARQUEO
    Route::get('/arqueo', 'ArqueController@index')->name('arqueo.index');
    Route::get('/arqueo/tabla', 'ArqueController@tabla')->name('arqueo.tabla');
    Route::get('/arqueo/detalle/{id}', 'ArqueController@show')->name('arqueo.show');
    Route::get('/arqueo/tabla/{id}', 'ArqueController@tablashow')->name('arqueo.show.tabla');
    Route::put('/arqueo/update/{id}', 'ArqueController@update')->name('arqueo.update');
    Route::post('/arqueo/store', 'ArqueController@store')->name('arqueo.store');
    Route::post('/arqueo/detalle/store', 'ArqueController@storeshow')->name('arqueo.store.show');
    Route::get('/arqueo/pagos/{id}', 'ArqueController@pagos')->name('arqueo.pago.show');
    Route::get('/arqueo/tabla-pago/{id}', 'ArqueController@tablapago')->name('arqueo.pago.tabla');

//    CONFIGURACION
    Route::get('configuracion', 'ConfigController@index')->name('configuracion')->middleware('superv.admin');
    Route::post('config/create', 'ConfigController@create')->name('config.create')->middleware('superv.admin');
    Route::get('config/{id}/editar', 'ConfigController@edit')->name('configuracion.editar')->middleware('superv.admin');
    Route::patch('config/{id}', 'ConfigController@update')->name('configuracion.update')->middleware('superv.admin');


//    USUARIOS
    Route::get('/usuarios', 'UsuarioController@index')->name('usuarios.index')->middleware('can:Superadmin');
    Route::get('/usuarios/tabla', 'UsuarioController@tabla')->name('usuarios.tabla')->middleware('can:Superadmin');
    Route::post('/usuarios/store', 'UsuarioController@store')->name('usuarios.store')->middleware('can:Superadmin');
    Route::put('/usuarios/update/{id}', 'UsuarioController@update')->name('usuarios.update')->middleware('can:Superadmin');
    Route::put('/usuarios/delete/{id}', 'UsuarioController@delete')->name('usuarios.delete')->middleware('can:Superadmin');
    

    //    CUENTA CORRIENTE
    Route::get('cuenta-corriente-inicio', 'CuentaCorrienteController@index')->name('corriente.index')->middleware('supervendedor');
    Route::get('cuenta-corriente-tabla', 'CuentaCorrienteController@tabla')->name('corriente.tabla')->middleware('supervendedor');
    Route::get('cuenta-corriente-ver/{id}', 'CuentaCorrienteController@show')->name('corriente.show')->middleware('superv.admin');
    Route::post('corriente/update/{id}', 'CuentaCorrienteController@update')->name('corriente.update')->middleware('superv.admin');

    // SUCURSALES
    Route::get('/sucursales', 'SucursalController@index')->name('sucursal.index')->middleware('superv.admin');
    Route::get('/sucursales/tabla', 'SucursalController@tabla')->name('sucursal.tabla')->middleware('superv.admin');
    Route::post('/sucursales/store', 'SucursalController@store')->name('sucursal.store')->middleware('superv.admin');
    Route::put('/sucursales/delete/{id}', 'SucursalController@delete')->name('sucursal.delete')->middleware('superv.admin');
    Route::put('/sucursales/update/{id}', 'SucursalController@update')->name('sucursal.update')->middleware('superv.admin');


    //INFORMES
    Route::get('/informes', 'InformeController@index')->name('informe.index')->middleware('superv.admin');
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@avisos'])->middleware('supervendedor');
   


    //    GASTOS
    Route::get('/gastos', 'GastosController@index')->name('gastos.index');
    Route::get('/gastos/tabla', 'GastosController@tabla')->name('gastos.tabla');
    Route::get('/gastos/tablatipo', 'GastosController@tablatipo')->name('gastos.tablatipo');
    Route::post('/gastos/store', 'GastosController@store')->name('gastos.store');
    Route::post('/gastos/storetp', 'GastosController@storetp')->name('gastos.storetp');
    Route::patch('/gastos/updatetg/{id}', 'GastosController@updatetg')->name('gastos.updatetg');
    Route::patch('/gastos/update/{id}', 'GastosController@update')->name('gastos.update');
    Route::delete('gastos-deletetg/{id}', 'GastosController@destroytg')->name('gastos.destroytg');
    Route::delete('gastos-delete/{id}', 'GastosController@destroy')->name('gastos.destroy');
    Route::post('/consultagasto', 'GastosController@consultagasto')->name('consultagasto');
    Route::get('/tablatotalgasto', 'GastosController@tabla_total')->name('tablatotalgasto');



});

Auth::routes();

