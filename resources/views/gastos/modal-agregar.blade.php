<div class="modal modal-success fade in" id="modal-agregar-gasto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i data-toggle="tooltip" title="Agregar Gasto" class="fa fa-plus-circle"></i> Agregar Gasto</h4>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
                {!! Form::open(['route' => 'gastos.store', 'method'=>'POST', 'id'=>'agregar_form','autocomplete' => 'off', 'files' => 'true']) !!}
                {{Form::token()}}
                    <div class="row">

                    
                    
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Tipo de gasto</label>
                            <select name="tipo_gasto" id="tipo_gasto1" class="form-control">
                                @foreach ($tipogastos as $tipogasto)
                                    <option value="{{$tipogasto->id_tipo_gasto}}">{{$tipogasto->gasto_nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="codigo">Importe</label>
                            <input type="number" required  value="0.00" name="importe" id="importe" class="form-control" placeholder="Importe del gasto.">
                        </div>
                    </div>
                    
                    
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="codigo">Fecha</label>
                        <div class='input-group date' id='datetimepicker5'>
                                <input type='date' value="{{$datenow}}" name="fecha" id="fecha" class="form-control" placeholder="Inicio de Fecha" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                  
                    

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="stock">Comprobante asociado</label>
                            <input type="text" required   name="comprobante" class="form-control" placeholder="comprobante asociado.">
                        </div>
                    </div>
    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="descripcion">Detalle</label>
                            <textarea  name="detalle"   class="form-control" placeholder="Detalle de la operacion..." cols="20" rows="10"> </textarea>
                        </div>
                    </div>
                  
                  
                    
                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i class="fa fa-window-close"></i> Cancelar</button>
                <button type="submit" id="agregar_form" form="agregar_form" class="btn btn-outline  btn-xs"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



