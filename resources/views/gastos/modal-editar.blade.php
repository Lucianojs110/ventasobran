<div class="modal modal-success fade in" id="modal-editar-gasto{{$gas->id_gasto}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i data-toggle="tooltip" title="Agregar Gasto" class="fa fa-plus-circle"></i> Editar Gasto</h4>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
            {!!Form::model($gas,['route'=>['gastos.update', $gas->id_gasto] , 'method'=>'PATCH', 'files'=>'true', 'id'=>'edit-'.$gas->id_gasto] )!!}
                {{Form::token()}}
                 
                    <div class="row">

                    
                    
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Tipo de gasto</label>
                            <select name="tipo_gasto" class="form-control">
                                @foreach ($tipogastos as $tipogasto)
                                    @if ($gas->id_tipo_gasto == $tipogasto->id_tipo_gasto)
                                    <option selected value="{{$tipogasto->id_tipo_gasto}}">{{$tipogasto->gasto_nombre}}</option>
                                    @else
                                    <option value="{{$tipogasto->id_tipo_gasto}}">{{$tipogasto->gasto_nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="codigo">Importe</label>
                            <input type="number" value="{{$gas->importe}}" required  value="0.00" name="importe" class="form-control" placeholder="Importe del gasto.">
                        </div>
                    </div>
                    
                    
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="codigo">Fecha</label>
                        <div class='input-group date' id='datetimepicker5'>
                                <input type='date' value="{{$gas->fecha}}" name="fecha" id="fecha" class="form-control" placeholder="Inicio de Fecha" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                  
                    

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="stock">Comprobante asociado</label>
                            <input type="text"  value="{{$gas->comprobante}}" name="comprobante" class="form-control" placeholder="comprobante asociado.">
                        </div>
                    </div>
    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="descripcion">Detalle</label>
                            <textarea   name="detalle"   class="form-control" placeholder="Detalle de la operacion..." cols="20" rows="10"> {{$gas->detalle}}</textarea>
                        </div>
                    </div>
                  
                  
                    
                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i class="fa fa-window-close"></i> Cancelar</button>
                <button type="submit"  form="edit-{{$gas->id_gasto}}"  class="btn btn-outline  btn-xs"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

