<div class="modal modal-info fade in" id="modal-editar-{{$tg->id_tipo_gasto}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i data-toggle="tooltip" title="Editar tipo gasto: {{$tg->gasto_nombre}}"
                                           class="fa fa-edit"></i> Editar tipo gasto</h4>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;"
                 class="modal-body">
                {!!Form::model($tg,['route'=>['gastos.updatetg', $tg->id_tipo_gasto] , 'method'=>'PATCH', 'files'=>'true', 'id'=>'edit-'.$tg->id_tipo_gasto] )!!}
                {{Form::token()}}
                 
                   
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                            <label for="nombre">Gasto</label>
                            <input type="text" value="{{$tg->gasto_nombre}}" required id="tgnombre" name="tgnombre" class="form-control"
                                   placeholder="Gasto">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                 <label for="descripcion">Importe</label>
                                <input type="number" value="{{$tg->importe}}" required name="tgimporte" id="tgimporte" class="form-control"
                                   placeholder="Importe">
                            </div>
                        </div>
                    </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left btn-xs" data-dismiss="modal"><i class="fa fa-window-close"></i> Cancelar</button>
                <button type="submit" form="edit-{{$tg->id_tipo_gasto}}" class="btn btn-outline btn-xs"> <i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>