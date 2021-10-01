@extends('layouts.app')
@section('css')
{{--<link rel="stylesheet" href="{{URL::to('/')}}/admin/css/jquery.dataTables.min.css">--}}
<style>
    table.dataTable tfoot th,
    table.dataTable tfoot td {
        padding: 0px 0px 0px 0px;
        border-top: 1px solid #111;
    }

    .table-wrapper {
        max-height: 150px;
        overflow: auto;
    }

    .loader2 {
        display: none;
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
       
     
    }

    

    .cargando {
        position: fixed;
        font-size: 35px;
        left: 50%;
        top: 60%;
        width: 100%;
        height: 100%;
        z-index: 9998;

    }

   
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('content')
<section class="content">
    <div class="box">
        <div class="box-header with-border">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Informes</h3>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 5px">
                        <div class='input-group date' id='datetimepicker5'>
                            <input type='date' name="start_date" id="start_date" class="form-control" placeholder="Inicio de Fecha" />
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 5px">
                        <div class='input-group date' id='datetimepicker7'>
                            <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Final Fecha" />
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 5px">

                        <select id="sucursal" class="form-control">
                            <option value="0">Todas las sucursales</option>
                            @foreach ($sucursal as $sucursales)
                            <option value="{{$sucursales->id}}">{{$sucursales->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 5px">
                        <button id="btnFiterSubmitSearch" class="btn btn-info"><i class="fa fa-search"></i> Aplicar Filtro</button>
                    </div>
                </div>
            </div>

            <br><br>

            <div class="loader2" id="loader2">
                <div class="cargando"><b> Cargando ... </b></div>
            </div>

            <div id="loader">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px">
                    <table class="table" style="font-size: 20px; background-color:#D5F5E3">
                        <thead style="background-color:#E5E7E9 ">
                            <tr>

                                <th scope="col" style="width:50%">Ingresos</th>
                                <th scope="col">Importe</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ventas en efectivo</td>
                                <td id="efectivo">$0</td>
                            </tr>
                            <tr>
                                <td>Ventas en tarjeta de credito</td>
                                <td id="credito">$0</td>

                            </tr>
                            <tr>
                                <td>Ventas en tarjeta débito</td>
                                <td id="debito">$0</td>
                            </tr>

                            <tr>
                                <td>Ventas registradas</td>
                                <td id="facturado">$0</td>
                            </tr>

                            <tr>
                                <td>Ventas no registradas</td>
                                <td id="no_facturado">$0</td>
                            </tr>

                            <tr>
                                <td>Total</td>
                                <td id="total">$0</td>
                            </tr>

                        </tbody>
                    </table>
                </div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                    <table class="table" style="font-size: 20px; background-color:#FDEBD0">
                        <thead style="background-color:#E5E7E9">
                            <tr>
                                <th scope="col" style="width:50%">Egresos</th>
                                <th scope="col"></th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <td>Compras de stock</td>
                                <td id="total_compra">$0</td>
                            </tr>
                        </tbody>
                    </table>


                    <table class="table"  style="font-size: 20px; background-color:#FDEBD0">
                        <thead style="background-color:#E5E7E9">
                            <tr>
                                <th style="width:50%">Gastos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="bodytablagastos">

                        </tbody>
                    </table>

                </div>
                <!--resultado final -->

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 25px">

                    <table class="table" style="font-size: 20px; background-color:#D5F5E3">
                        <thead style="background-color:#E5E7E9">
                            <tr>
                                <th>Total Ingresos</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td id="total_ingreso">$0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 25px">

                    <table class="table" style="font-size: 20px; background-color:#FDEBD0">
                        <thead style="background-color:#E5E7E9">
                            <tr>
                                <th>Total Egresos</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td id="total_egreso">$0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 25px">

                    <table class="table" style="font-size: 20px; background-color:#AED6F1">
                        <thead style="background-color:#E5E7E9">
                            <tr>
                                <th>Saldo</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td id="saldo">$0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        tabla()
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#btnFiterSubmitSearch').click(function() {
        $('#ven').DataTable().ajax.reload();
        tabla()

    });



    function tabla() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "{{route('tablainforme')}}",
            dataType: "json",
            beforeSend: function() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("loader2").style.display = "block";
            },
            data: {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                sucursal: $('#sucursal').val()
            },
            success: function(data) {

                console.log(data);
                document.getElementById("loader").style.display = "block";
                document.getElementById("loader2").style.display = "none";

                $("#bodytablagastos").html("");


                var efectivo = data[0].venta[0].Total - data[0].venta[0].Debito - data[0].venta[0].Credito;

                if (data[0].venta[0].Total == null) {
                    $('#total').text('$0');
                } else {
                    $('#total').text('$' + data[0].venta[0].Total);
                }

                if (efectivo == null) {
                    $('#efectivo').text('$0');
                } else {
                    $('#efectivo').text('$' + parseFloat(efectivo).toFixed(2));
                }

                if (data[0].venta[0].Debito == null) {
                    $('#debito').text('$0');
                } else {
                    $('#debito').text('$' + data[0].venta[0].Debito);
                }

                if (data[0].venta[0].Credito == null) {
                    $('#credito').text('$0');
                } else {
                    $('#credito').text('$' + data[0].venta[0].Credito);
                }

                if (data[0].facturado[0].total_fac == null) {
                    $('#facturado').text('$0');
                } else {
                    $('#facturado').text('$' + data[0].facturado[0].total_fac);
                }

                if (data[0].no_facturado[0].total_no_fac == null) {
                    $('#no_facturado').text('$0');
                } else {
                    $('#no_facturado').text('$' + data[0].no_facturado[0].total_no_fac);
                }

                if (data[0].compra[0].Total == null) {
                    $('#total_compra').text('$0');
                } else {
                    $('#total_compra').text('$' + data[0].compra[0].Total);
                }

                if(data[0].gastos.length==0){
                    
                    $("#bodytablagastos").append('<tr><td style="width:50%">No hay gastos registrados</td> </tr>')

                }
                
                
                
                for (i = 0; i < data[0].gastos.length; i++) { //cuenta la cantidad de registros

                    var nuevafila = '<tr><td style="width:50%"> ' + data[0].gastos[i].gasto_nombre + ' </td> <td> ' + data[0].gastos[i].importe + ' </td> </tr>'
                    $("#bodytablagastos").append(nuevafila)

                }


                $('#total_egreso').text('$' + parseFloat(data[0].gasto_total).toFixed(2));

                if (data[0].venta[0].Total == null) {
                    $('#total_ingreso').text('$0.00');
                } else {
                    $('#total_ingreso').text('$' + parseFloat(data[0].venta[0].Total).toFixed(2));
                }


                let saldo = data[0].venta[0].Total - data[0].gasto_total;
                $('#saldo').text('$' + parseFloat(saldo).toFixed(2));



            },

        });
        return false;
    }
</script>
@stop