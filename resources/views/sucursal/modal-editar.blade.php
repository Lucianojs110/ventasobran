<div class="modal fade modal-info" id="modal-editar-{{$sucursal->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Editar sucursalursal: {{$sucursal->nombre}}</h5>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
                {!!Form::model($sucursal,['route'=>['sucursal.update', $sucursal->id] , 'id'=>'edit-'.$sucursal->id.'', 'method'=>'put', 'enctype'=>'multipart/form-data'])!!}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Nombre</label>
                        <input  value="{{$sucursal->nombre}}" type="text" required name="nombre" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Direccion</label>
                        <input required value="{{$sucursal->direccion}}" required type="text" name="direccion" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Ciudad</label>
                        <input value="{{$sucursal->ciudad}}" required type="text" name="ciudad" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Cuit</label>
                        <input value="{{$sucursal->cuit}}" required type="text" name="cuit" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Ingresos Brutos</label>
                        <input value="{{$sucursal->ingresos_brutos}}" required type="text" name="ingresos_brutos" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Telefono</label>
                        <input value="{{$sucursal->telefono}}" required type="text" name="telefono" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Correo</label>
                        <input value="{{$sucursal->email}}" required type="email" name="email" class="form-control">
                    </div>
                    <div class="py-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Condicion frente al Iva</label>
                            <select name="impuesto" class="form-control">
                                @if($sucursal->impuesto == '11')
                                    <option selected value="11">Monotributo</option>
                                    <option value="6">Resp. Inscripto</option>
                                @else
                                    <option value="6">Resp. Inscripto</option>
                                    <option selected value="11">Monotributo</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" form="edit-{{$sucursal->id}}" class="btn btn-outline  btn-xs"><i
                            class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>