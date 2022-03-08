@extends('layouts.app')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h3>Listado de Ventas
                            <a href="{{route('venta.create')}}">
                                <button class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo Venta</button>
                            </a>
                        </h3>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class='input-group date' id='datetimepicker5'>
                                <input type='date' name="start_date" id="start_date" class="form-control" placeholder="Inicio de Fecha" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class='input-group date' id='datetimepicker7'>
                                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Final Fecha"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <button id="btnFiterSubmitSearch" class="btn btn-info"><i class="fa fa-search"></i> Aplicar Filtro</button>
                        </div>
                    </div>
                </div>
                @foreach($ventas as $ven)
                    @include('ventas.venta.modal')
                @endforeach


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <div  class="table-responsive">
                            <table id="ven" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Comprobante</th>
                                <th>Impuesto</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                                </thead>
                                <tfoot>
                                <tr style="font-size: 20px">
                                 <td id="efectivo"></td>
                                 <td id="credito"></td>
                                 <td id="debito"></td>
                                 <td id="total"></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            tabla_total()

            $('#ven').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            order: [[2, "desc"]],
            ajax: {
                url: "{{route('venta.tabla')}}",
                type: 'GET',
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
            },
            columns: [
                {data: 'fecha', name: 'fecha'},
                {data: 'cliente', name: 'cliente'},
                {data: 'comprobante', name: 'comprobante'},
                {data: 'impuesto', name: 'impuesto'},
                {data: 'total_venta', name: 'total_venta'},
                {data: 'estado', name: 'estado'},
                {data: 'opcion', name: 'opcion', orderable: false, searchable: false}
            ],
            
            "language": {
                "url": "{{URL::to('/')}}/admin/Spanish.json"
            },
           
        });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

            
        });
    
        
        $('#btnFiterSubmitSearch').click(function () {
            $('#ven').DataTable().ajax.reload();
            tabla_total()
 
        });



        function tabla_total()
             {
             $.ajax({
                   headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                   type: "GET",
                   url: "{{route('tablatotal')}}",
                   dataType: "json",
                   data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val()
                   },
                   success: function(data) {
                    
                    var efectivo = data[0].Total - data[0].Credito - data[0].Debito;
                    
                    if(data[0].Total==null){
                        $('#total').text('Total $0');
                    }else{
                        $('#total').text('Total  $'+data[0].Total);
                    }

                    if(data[0].Debito==null){
                        $('#debito').text('Debito $0');
                    }else{
                        $('#debito').text('Debito $'+data[0].Debito);
                    }

                    if(data[0].Credito==null){
                        $('#credito').text('Credito $0');
                    }else{
                        $('#credito').text('Credito $'+data[0].Credito);
                    }

                    if(data[0].Efectivo==null){
                        $('#efectivo').text('Efectivo $0');
                    }else{
                        $('#efectivo').text('Efectivo $'+parseFloat(efectivo).toFixed(2));
                    }
                   
                    
                   },
                  
               });
               return false;
             } 

        

       
    </script>
@stop
