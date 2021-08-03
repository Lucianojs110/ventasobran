<div class="modal modal-success fade in" id="modal-act-precios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i data-toggle="tooltip"  class="fa fa-money"></i></i> Actualización de precios</h4>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
                
               
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Seleccione una Categoría</label>
                            <select name="idcategoria-act" id="idcategoria-act" class="form-control">
                                @foreach ($categorias as $cat)
                                    <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Acción</label>
                            <select id="accion" class="form-control">   
                                    <option value="1">Aumentar Precios</option>
                                    <option value="2">Reducir Precios</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label for="codigo">Porcentaje</label>
                            <input type="number" id="porcentaje" class="form-control" min="1" max="100">
                        </div>
                    </div>
                    
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i class="fa fa-window-close"></i> Cancelar</button>
                <button type="submit" id="actualizar-precios" s class="btn btn-outline  btn-xs"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

