<div class="modal modal-info fade in" id="modal-editar-{{$cli->idpersona}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i data-toggle="tooltip" title="Editar cliente: {{$cli->nombre}}"
                                           class="fa fa-edit"></i> Editar cliente: {{$cli->nombre}}</h4>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;"
                 class="modal-body">
                {!!Form::model($cli,['route'=>['cliente.update', $cli->idpersona] , 'method'=>'PATCH', 'files'=>'true', 'id'=>'edit-'.$cli->idpersona] )!!}
                {{Form::token()}}
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Documento</label>
                            <select name="tipo_documento" class="form-control">
                                <option {{ $cli->tipo_documento == 'DNI' ? 'selected' : '' }} value="DNI">DNI</option>
                                <option {{ $cli->tipo_documento == 'CUIT' ? 'selected' : '' }} value="CUIT">CUIT</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="num_documento">Número documento</label>
                            <input value="{{$cli->num_documento}}" type="text" name="num_documento" class="form-control"
                                   placeholder="Número de documento...">
                        </div>
                    </div>
               
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" value="{{$cli->nombre}}" required name="nombre" class="form-control" placeholder="Nombre...">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="nombre">Dirección</label>
                            <input type="text" required name="direccion" value="{{$cli->direccion}}" class="form-control"
                                   placeholder="Dirección...">
                        </div>
                    </div>
                   
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" value="{{$cli->telefono}}" name="telefono" class="form-control" placeholder="Teléfono...">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="{{$cli->email}}" class="form-control" placeholder="Email...">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                            <label>Tipo de Cliente</label>
                             <input type="text" class="form-control" value="{{$cli->tipo_contribuyente}}" readonly name="tipo" id="tipo" placeholder="escribe el tipo de cliente">
                            </div>
                        </div>
                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" form="edit-{{$cli->idpersona}}" class="btn btn-outline  btn-xs"><i
                            class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>