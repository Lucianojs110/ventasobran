<div class="modal fade modal-success" id="agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><i data-toggle="tooltip" title="Agregar Sucursal" class="fa fa-plus-circle"></i> Agregar Sucursal</h5>
            </div>
            <div class="modal-body" style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;">
                {!! Form::open(['route' => 'sucursal.store', 'method'=>'POST', 'autocomplete' => 'off', 'files' => 'true','id'=>'agregar_form' , 'enctype'=>'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Nombre</label>
                        <input required type="text" name="nombre" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Direccion</label>
                        <input required type="text" name="direccion" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Ciudad</label>
                        <input required type="text" name="ciudad" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Cuit</label>
                        <input required type="text" name="cuit" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Ingresos Brutos</label>
                        <input required type="text" name="ingresos_brutos" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Telefono</label>
                        <input required type="text" name="telefono" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Email</label>
                        <input required type="text" name="email" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        
                        <label for="">Condicion frente al Iva</label>
                        <select name="impuesto" class="form-control">
                            <option selected value="11">Monotributo</option>
                            <option value="6">Resp. Inscripto</option>
                        </select>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Punto de venta afip</label>
                        <input  required type="number" name="punto" class="form-control">
                    </div>


                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Sincronizar con tienda WC</label>
                        <div class="form-group">
                            <select name="sincronizar" class="form-control">
                                <option selected value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                        
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Url API WooCommerce </label>
                            <input type="text"  name="urlAPIwoo"
                                placeholder="Ingrese la url de la API" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Clave API WooCommerce</label>
                            <input type="text"  name="ckAPIwoo"
                                placeholder="Clave Api" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Clave Usuario API WooCommerce </label>
                            <input type="text"  name="csAPIwoo"
                                placeholder="Clave Usuario" class="form-control">
                        </div>
                    </div>


                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i class="fa fa-window-close"></i> Cancelar</button>
                <button type="submit" form="agregar_form" class="btn btn-outline  btn-xs"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>