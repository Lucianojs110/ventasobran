<div class="modal modal-info" aria-hidden="true" role="dialog" tabindex="-1" id="modal_fecha">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden='true'>x</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-calculator"></i> Ganancia por mes de arqueo</h4>
            </div>
            <div class="modal-body" style="overflow-y: auto;background-color: #ffffff !important; color: black !important;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Mes/Año</th>
                            <th>Pago en Efectivo</th>
                            <th>Pago en Debito</th>
                            <th>Pago en Credito</th>
                            
                           
                            <th>Ganancia</th>
                        </thead>
                        @foreach ($ganancias as $ganancia)
                            <tbody>
                            <tr>
                                <td>{{$ganancia->month}}/{{$ganancia->year}}</td>
                                <td>$ {{$ganancia->efectivo}}</td>
                                <td>$ {{$ganancia->debito}}</td>
                                <td>$ {{$ganancia->credito}}</td>
                                <td>$ {{($ganancia->data)}}</td>
                            </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-outline  btn-xs" href="{{route('pdf.arqueo')}}" ><i class="fa fa-print"></i> Descargar PDF</a>
                <button type="button" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i class="fa fa-window-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>