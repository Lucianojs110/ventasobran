<div class="modal fade modal-info" id="modal-editar-{{$suc->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Editar sucursal: {{$suc->nombre}}</h5>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
                {!!Form::model($suc,['route'=>['usuarios.update', $suc->id] , 'id'=>'edit-'.$suc->id.'', 'method'=>'put', 'enctype'=>'multipart/form-data'])!!}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Nombre</label>
                        <input  value="{{$suc->nombre}}" type="text" name="nombre" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Direccion</label>
                        <input required value="{{$suc->direccion}}" type="text" name="direccion" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Ciudad</label>
                        <input value="{{$suc->ciudad}}" type="text" name="ciudad" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Cuit</label>
                        <input value="{{$suc->cuit}}" type="text" name="cuit" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Ingresos Brutos</label>
                        <input value="{{$suc->ingresos_brutos}}" type="text" name="ingresos_brutos" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Telefono</label>
                        <input value="{{$suc->telefono}}" type="text" name="telefono" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Correo</label>
                        <input value="{{$suc->email}}" type="email" name="email" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Impuesto</label>
                        <input value="{{$suc->impuesto}}" type="text" name="impuesto" class="form-control">
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" form="edit-{{$suc->id}}" class="btn btn-outline  btn-xs"><i
                            class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>