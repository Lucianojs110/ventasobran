<div class="modal fade modal-danger" id="modal-borrar-{{$suc->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Borrar sucursal: {{$suc->name}}</h5>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;"  class="modal-body">
                <h4>¿Esta Seguro que desea borrar la sucursal?</h4>
                {!!Form::model($suc,['route'=>['sucursal.delete', $suc->id] , 'id'=>'borrar-'.$suc->id.'', 'method'=>'put'])!!}
                    <input type="hidden" name="id" value="{{$suc->id}}">
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" form="borrar-{{$suc->id}}" class="btn btn-outline  btn-xs"><i
                            class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>