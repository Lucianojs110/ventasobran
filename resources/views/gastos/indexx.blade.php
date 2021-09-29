@extends('layouts.app')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('content')
<section class="content">
    <div class="box">
        <div class="box-header with-border">


            <div role="tabpanel">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#gasto" aria-controls="gasto" role="tab" data-toggle="tab">Listado de Gastos</a>
                    </li>
                    <li role="presentation">
                        <a href="#tipo_gasto" aria-controls="tipo_gasto" role="tab" data-toggle="tab">Gastos predefinidos</a>
                    </li>

                </ul>

                <!-- Tab gastos-->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="gasto">


                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <h3>Listado de Gastos
                                    <button data-toggle="modal" data-target="#modal-agregar-gasto" class="btn btn-xs btn-success"><i data-toggle="tooltip" title="Agregar Gasto" class="fa fa-plus"></i>
                                    </button>
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
                                        <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Final Fecha" />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <button id="btnFiterSubmitSearch" class="btn btn-info"><i class="fa fa-search"></i> Aplicar Filtro</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <div class="table-responsive">
                                    <table id="gas" style="width:100%" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Gasto</th>
                                            <th>Importe</th>
                                            <th>Opciones</th>
                                        </thead>
                                        <tfoot>
                                            <tr style="font-size: 20px">
                                                <td id="total"></td>
                                                <td></td>
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

                    @foreach($gastos as $gas)
                    @include('gastos.modal-editar')
                    @include('gastos.modal-borrar')
                    @endforeach


                    <!-- Tab tipo gastos-->
                    <div role="tabpanel" class="tab-pane" id="tipo_gasto">
                        <br>
                        <div class="row ">

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h3>Nuevo tipo de gasto</h3>
                                {!! Form::open(['route' => 'gastos.storetp', 'method'=>'post']) !!}
                                {{Form::token()}}
                                <div class="form-group">
                                    <label for="nombre">Gasto</label>
                                    <input type="text" required id="tgnombre" name="tgnombre" class="form-control" placeholder="Gasto">
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Importe</label>
                                    <input type="number" required name="tgimporte" id="tgimporte" class="form-control" placeholder="Importe">
                                </div>

                                <div class="form-group">
                                    <button data-toggle="tooltip" title="Guardar tipo de gasto" class="btn btn-primary btn-xs" type="submit"><i class="fa fa-save"></i> Guardar
                                    </button>
                                    <button data-toggle="tooltip" title="Cancelar" class="btn btn-danger btn-xs" type="reset"><i class="fa fa-window-close"></i> Cancelar
                                    </button>
                                </div>

                                {!!Form::close()!!}
                            </div>

                            @foreach($tipogastos as $tg)
                            @include('gastos.modal-editar-tg')
                            @include('gastos.modal-borrar-tg')
                            @endforeach

                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <div class="table-responsive" style="width:100%">
                                    <table id="tp" style="width:100%" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                            <th>Nombre</th>
                                            <th>importe</th>
                                            <th>Opciones</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>
    </div>
</section>
@include('gastos.modal-agregar')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        tabla_total();
        $('[data-toggle="tooltip"]').tooltip();
        

        $('#tipo_gasto1').on('change', function() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{route('consultagasto')}}",
                dataType: "json",
                data: {
                    idgasto: $(this).val(),
                },
                success: function(data) {
                    console.log(data);
                    $('#importe').val(data[0].importe);
                },

            });
            return false;
        });
    });
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#tp').DataTable({
        processing: true,
        serverSide: true,
        iDisplayLength: 10,
        order: [
            [1, "desc"]
        ],
        ajax: {
            url: "{{route('gastos.tablatipo')}}",
            type: 'GET',

        },
        columns: [{
                data: 'nombre',
                name: 'nombre'
            },
            {
                data: 'importe',
                name: 'importe'
            },


            {
                data: 'opcion',
                name: 'opcion',
                orderable: false,
                searchable: false
            }
        ],

        "language": {
            "url": "{{URL::to('/')}}/admin/Spanish.json"
        },

    });


    $('#gas').DataTable({
        processing: true,
        serverSide: true,
        iDisplayLength: 10,
        order: [
            [2, "desc"]
        ],
        ajax: {
            url: "{{route('gastos.tabla')}}",
            type: 'GET',
            data: function(d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            },
        },
        columns: [{
                data: 'fecha',
                name: 'fecha'
            },
            {
                data: 'usuario',
                name: 'usuario'
            },
            {
                data: 'gasto',
                name: 'gasto'
            },
            {
                data: 'importe',
                name: 'importe'
            },

            {
                data: 'opcion',
                name: 'opcion',
                orderable: false,
                searchable: false
            }
        ],

        "language": {
            "url": "{{URL::to('/')}}/admin/Spanish.json"
        },

    });

    $('#btnFiterSubmitSearch').click(function() {
        $('#gas').DataTable().ajax.reload();
        tabla_total();
    });


    function tabla_total() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "{{route('tablatotalgasto')}}",
            dataType: "json",
            data: {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            },
            success: function(data) {
                if(data[0].Total==null){
                    $('#total').text('Total: $0');
                }else{
                $('#total').text('Total $' + data[0].Total);
                }
            },

        });
        return false;
    }
</script>
@stop