@extends('layouts.app')
@section('content')
<style>
    .rect-checkbox {
        float: left;
        margin-left: 130px;
    }

    .span {
        margin-left: -161px;
    }
</style>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h3>Nueva Venta</h3>
                    @if (count($errors)>0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            {!! Form::open(['route' => 'venta.store', 'method'=>'POST', 'autocomplete' => 'off', 'id'=>'cre', 'onSubmit'=>'return pagos()'])!!}
            {{Form::token()}}
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="row">

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group" id="clidv">
                        <label for="cliente">Cliente</label>
                        <select name="idcliente" class="form-control selectpicker" id="idcliente" required data-live-search="true">
                            <option value="1" selected>Consumidor final</option>
                            @foreach($personas as $persona)
                            <option value='{{$persona->idpersona}}_{{$persona->tipo_contribuyente}}_{{$persona->num_documento}}'>{{$persona->nombre}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" class="form-control" id="idcliente2" name="idcliente2" value="1">

                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Tipo De Venta</label>
                        <select name="tipo_venta" id="tipo_venta" class="form-control">
                            <option value="Cuenta Corriente">Cuenta Corriente</option>
                            <option selected value="Venta Comun">Venta Comun</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Tipo Comprobante</label>

                        @foreach($config2 as $config1)
                        @if($config1->impuesto==11)
                        <input type="text" readonly class="form-control" id="tipo_comprobante" name="tipo_comprobante" value="Factura C">
                        @else
                        <input type="text" readonly class="form-control" id="tipo_comprobante" name="tipo_comprobante" value="Factura B">
                        @endif
                        @endforeach

                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label for="num_comprobante">Número Comprobante</label>
                        @if ($ven == '1')
                        <input type="text" readonly value="0-0" name="num_comprobante" class="form-control" placeholder="Numero Comprobante">
                        @else
                        <input type="text" readonly value="0-{{$ven->idventa}}" name="num_comprobante" class="form-control" placeholder="Numero Comprobante">
                        @endif
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="cantidad">Código Articulo</label>
                                        <input type="text" name="pcodigo" id="pcodigo" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Nombre Articulo</label>
                                        <input type="text" name="pnombre" id="pnombre" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">

                                        <input type="hidden" name="pidarticulo" id="pidarticulo" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad</label>
                                        <input type="number" step="0.1" min="1" name="pcantidad" id="pcantidad" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" step=".01" disabled name="pstock" id="pstock" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label for="precio_venta">Precio Venta</label>
                                        <input type="number" step=".01" min="0.1" name="pprecio_venta" id="pprecio_venta" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label for="descuento">Descuento</label>
                                        <input type="number" step=".01" min="0.1" name="pdescuento" id="pdescuento" class="form-control" placeholder="Precio Compra">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group">
                                        <button type="button" id="bt-add" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Agregar</button>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                                    <table class="table table-striped table-bordered table-condensed table-hover">
                                        <thead style="background-color: #A9D0F5">
                                            <th>Opciones</th>
                                            <th>Artículos</th>
                                            <th>Cantidad</th>
                                            <th>Precio Venta</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </thead>
                                        <tbody id="detalles">
                                        </tbody>
                                        <tbody>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>
                                                <h4 id="total">$ 0.00</h4>
                                                <input  type="hidden" name="total_venta" id="total_venta">
                                                <input  type="hidden" name="total_des" id="total_des">
                                            </th>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="form-group">
                                <h4 style="font-weight: 900;" id="total2"> Total Compra: $ 0.00</h4>
                                <h4 style="font-weight: 900;" id="totalrecargo"></h4>
                            </div>
                            <div class="row">

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label for="tarjeta_debito">Tarjeta de Debito</label>
                                    <input type="number" form="cre" class="form-control" value="0" id="tarjeta_debito" placeholder="Tarjeta de Debito" step="0.01" min="0" name="tarjeta_debito">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label for="tarjeta_credito">Tarjeta de Credito</label>
                                    <input type="number" form="cre" class="form-control" value="0" id="tarjeta_credito" placeholder="Tarjeta de Credito" step="0.01" min="0" name="tarjeta_credito">
                                </div>



                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group" id="pa1">
                                        <label for="tarjeta_debito">Porcentaje de Credito</label>
                                        <input type="hidden" form="cre" step="any" onchange="porcentaje()" onkeyup="porcentaje()" class="form-control" value="0" min="0" id="porcentaje_credito" name="porcentaje_credito">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group" id="pa1">
                                        <label for="tarjeta_debito">Dinero del Porcentaje</label>
                                        <input type="hidden"form="cre" step="any" onchange="porcentajeDos()" onkeyup="porcentajeDos()" class="form-control" value="0" min="0" id="monto_porcentaje" name="monto_porcentaje">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                    <label for="efectivo">Descuento</label>
                                    <input type="number" form="cre" class="form-control" onchange="des()" onkeyup="des()" value="0" id="descuento_total" placeholder="Descuento" min="0" max="99" step="any" name="descuento_total">

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label for="efectivo">Efectivo</label>
                                    <input type="number" form="cre" class="form-control" value="0" id="paga" placeholder="Efectivo" min="0" step="0.01" name="paga">
                                </div>

                            </div>


                        </div>
                    </div>
                    <div class="" id="mens">
                    </div>
                    <div class="container-fluid" id="guardar">
                        <div class="form-group">
                            <input form="cre" type="hidden" name="_token" value="{{csrf_token()}}">
                            <button form="cre" id="guardar" class="btn btn-primary pull-left btn-xs btn-flat" type="submit"><i class="fa fa-save"></i> Guardar </button>
                            <button form="cre" class="btn btn-danger pull-right btn-xs btn-flat" type="reset"><i class="fa fa-window-close"></i> Cancelar</button>

                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="idusuario" value="{{Auth::user()->id}}">
            {!!Form::close()!!}
        </div>
    </div>

    <!-- inicio ticket -->

    <div style="display: none">
        <div class="row">
            <div id="table"  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                <table>
                    <thead>
                        <th style="font-size: 12px; text-align:left">Cant</th>
                        <th style="font-size: 12px; text-align:left">Art</th>
                        <th style="font-size: 12px; text-align:left">P.Unit</th>
                        <th style="font-size: 12px; text-align:left">sTotal</th>
                    </thead>
                    <tbody id="detalles2">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="date" style="display: none">
        {{$Date}}
    </div>

    <h5  id="totalrecargo2"></h5>
    <!--<h5 style="display: none" id="totalrecargo2"></h5>-->


    <!-- Fin ticket -->

</section>
@push('scripts')

<script>
    function porcentaje() {

        var porcentaje_credito = parseFloat($("#porcentaje_credito").val()).toFixed(2);
        var tarjeta_credito = parseFloat($("#tarjeta_credito").val()).toFixed(2);

        var result = parseFloat((porcentaje_credito / 100) * tarjeta_credito).toFixed(2);

        var monto_porcentaje = parseFloat($("#monto_porcentaje").val(result)).toFixed(2);

        var totalventa = parseFloat($("#total_venta").val()).toFixed(2);

        var total = (parseFloat(result) + parseFloat(totalventa)).toFixed(2);

        //console.log(total);

        if (porcentaje_credito >= 1) {
            $('#totalrecargo').html("Total con recargo: $" + parseFloat(total).toFixed(2));
        } else {
            $('#totalrecargo').html("");
        }

        $('#totalrecargo2').html(parseFloat(total).toFixed(2));

    }

    function porcentajeDos() {

        var monto_porcentaje = parseFloat($("#monto_porcentaje").val()).toFixed(2);

        var tarjeta_credito = parseFloat($("#tarjeta_credito").val()).toFixed(2);

        var result = (monto_porcentaje * 100) / tarjeta_credito;

        var porcentaje_credito = parseFloat($("#porcentaje_credito").val(result)).toFixed(2);

        

    }

    function des() {

        if ($("#descuento_total").val() != '') {
            var porcentaje_des = (parseFloat($("#descuento_total").val()).toFixed(2)) * 0.01;

        } else {
            porcentaje_des = 0;
            var totalventa = parseFloat($("#total_venta").val()).toFixed(2);
            $('#total_des').val(parseFloat(totalventa).toFixed(2));
            $('#total2').val(parseFloat(totalventa).toFixed(2));
        }


        var totalventa = parseFloat($("#total_venta").val()).toFixed(2);

        var des = totalventa * porcentaje_des;

        var venta_des = totalventa - des;

        $('#total2').html('Total Compra: $' + parseFloat(venta_des).toFixed(2));
        $('#total_des').val(parseFloat(venta_des).toFixed(2));
        $('#totalrecargo2').html(parseFloat(venta_des).toFixed(2));
        //console.log(venta_des);

    }

    function pagos() {
        var tarjeta_debito = $("#tarjeta_debito").val();
        var tarjeta_credito = $("#tarjeta_credito").val();
        var paga = $("#paga").val();
        var total_venta = parseFloat($("#total_des").val()).toFixed(2);
        var tipo_venta = $("#tipo_venta").val();


        if (tarjeta_debito == '') {
            tarjeta_debito = 0;
        }
        if (tarjeta_credito == '') {
            Fp
            tarjeta_credito = 0;
        }

        if (paga == '') {
            paga = 0;
        }

        if (tipo_venta == 'Cuenta Corriente') {
            return true;
        }

        var suma = parseFloat(paga) + parseFloat(tarjeta_credito) + parseFloat(tarjeta_debito);

        if (suma == total_venta || suma >= total_venta) {
            imprimir();
            return true;


        } else {
            if (suma < total_venta && suma != 0) {
                $("#pa1").removeClass("has-error");
                $("#pa2").removeClass("has-error");
                $("#pa3").removeClass("has-error");

                $("#pa1").addClass("has-success");
                $("#pa2").addClass("has-success");
                $("#pa3").addClass("has-success");


                var mens = '<div class="alert alert-success alert-dismissible">' +
                    '  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                    '  <strong>Atención!</strong> Debes ingresar el total de la venta o mayor del monto de la venta. El total de la venta es: <kbd>' + total_venta + '$</kbd> y usted ingreso <kbd>' + suma + '$</kbd>' +
                    '</div>';

                $('#mens').append(mens);


            }

            if (suma == 0) {
                $("#pa1").removeClass("has-success");
                $("#pa2").removeClass("has-success");
                $("#pa3").removeClass("has-success");

                $("#pa1").addClass("has-error");
                $("#pa2").addClass("has-error");
                $("#pa3").addClass("has-error");


                var mens = '<div class="alert alert-error alert-dismissible">' +
                    '  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                    '  <strong>Atención!</strong> Debes ingresar el total de la venta o mayor del monto de la venta. El total de la venta es: <kbd>' + total_venta + '$</kbd> y usted aun no ingreso dinero' +
                    '</div>';
                $('#mens').append(mens);

            }

            return false;
        }


    }
</script>

<script>
    $(document).ready(function() {

        $("#pcodigo").focus();

        $("#cre").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });


        $("#idcliente").change(tipoFactura);

        $('#bt-add').click(function() {
            agregar();
        });


        $("#pcodigo").keyup(function() {


            if ($("#pcodigo").val().length > 7) {
                consultar_codigo();

            }


        });


        $("#pcantidad").keypress(function(e) {
            if (e.which == 97) {
                agregar();
                $("#pcodigo").focus();

            }
        });

        $('#pnombre').autocomplete({
            source: function(request, response) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('consultaproducto')}}",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data)

                    },
                });

            },
            autoFocus: true,
            minLength: 1,
            select: function(event, selectedData) {
                $("#pprecio_venta").val(selectedData.item.precio);
                $("#pstock").val(selectedData.item.stock);
                $("#pcodigo").val(selectedData.item.codigo);
                $("#pidarticulo").val(selectedData.item.idarticulo);
                $("#pdescuento").val('0');
                $("#pcantidad").focus();
            }

        });

    });


    var cont = 0;
    total = 0;
    subtotal = [];
    total = 0;
    $("#guardar").hide();
    $("#pidarticulo").change(mostrarValores);

    function mostrarValores() {
        datosArticulo = document.getElementById('pidarticulo').value.split('_');
        $("#pprecio_venta").val(datosArticulo[2]);
        $("#pstock").val(datosArticulo[1]);
        $("#pcantidad").val('1');
    }

    function agregar() {

        var codigo = $("#pcodigo").val();
        var idarticulo = $("#pidarticulo").val();
        var articulo = $("#pnombre").val();
        var cantidad = $("#pcantidad").val();
        var descuento = $("#pdescuento").val();
        var precio_venta = parseFloat($("#pprecio_venta").val());
        var stock = $("#pstock").val();
        var stock_numero = parseInt(stock);
        var stock_cantidad = parseInt(cantidad);

        if (idarticulo != "" && cantidad != "" && cantidad > 0 && pdescuento != "" && precio_venta != "") {
            if (stock_numero >= stock_cantidad) {
                subtotal[cont] = (cantidad * precio_venta - descuento);
                total = total + subtotal[cont];
                var fila = '<tr class="selected" id="fila' + cont + '"><td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar(' + cont + ');">X</button></td><td><input type="hidden" name="articulo[]" value="' + articulo + '"><input type="hidden" name="codigo[]" value="' + codigo + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td><td><input type="hidden" name="cantidad[]" value="' + cantidad + '">' + cantidad + '</td><td><input type="hidden" name="precio_venta[]" value="' + precio_venta + '">$' + precio_venta + '</td><td><input type="hidden" name="descuento[]" value="' + descuento + '">$' + descuento + '</td><td>$' + subtotal[cont] + '</td></tr>';
                var fila2 = '<tr class="selected" id="fila2' + cont + '"><td style="font-size: 12px; width:20%"><input type="hidden" name="cantidad[]" value="' + cantidad + '">' + cantidad + ' x </td><td style="font-size: 12px; width:50%"><input type="hidden" name="articulo[]" value="' + articulo + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td><td style="font-size: 12px; width:50%"><input type="hidden" name="precio_venta[]" value="' + precio_venta + '">$' + precio_venta + '</td><td style="font-size: 12px; width:15%">$' + subtotal[cont] + '</td></tr>';

                cont++;
                limpiar();
                $('#total').html("$ " + parseFloat(total).toFixed(2));
                $('#total2').html("Total Compra: $" + parseFloat(total).toFixed(2));
                $('#totalrecargo2').html(parseFloat(total).toFixed(2));
                $('#total_venta').val(total);
                $('#total_des').val(total);

                evaluar();
                $('#detalles').append(fila);
                $('#detalles2').append(fila2);
            } else {
                alert('La cantidad a vender supera el stock');
            }

        } else {
            alert("Error al ingresar el detalle de la venta, revise los datos del artículo");
        }

    }

    function limpiar() {
        $('#pnombre').val("");
        $('#pcodigo').val("");
        $('#pidarticulo').val("");
        $('#pcantidad').val("");
        $('#pstock').val("");
        $('#pdescuento').val("");
        $('#pprecio_venta').val("");
        $('#pidarticulo').selectpicker('val', '0');
    }

    function evaluar() {
        if (total > 0) {
            $("#guardar").show();
        } else {
            $("#guardar").hide();
        }
    }

    function eliminar(index) {
        total = parseFloat(total).toFixed(2) - parseFloat(subtotal[index]).toFixed(2);
        parseFloat(total).toFixed(2)
        $("#total").html("$ " + parseFloat(total).toFixed(2));
        $('#total2').html("Total Compra: $" + parseFloat(total).toFixed(2));
        $('#total_venta').val(total);
        $('#total_des').val(total);
        $("#fila" + index).remove();
        $("#fila2" + index).remove();
        evaluar();
        $("#pcodigo").focus();
    }


    function consultar_codigo() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{route('consultacodigo')}}",
            dataType: "json",
            data: {
                codigoart: $("#pcodigo").val(),
            },
            success: function(data) {
                console.log(data);
                if (data.articulo.length > 0) {
                    $('#pstock').val(data.articulo[0].stock);
                    $('#pnombre').val(data.articulo[0].nombre);
                    $('#pprecio_venta').val(data.articulo[0].precio_venta);
                    $('#pidarticulo').val(data.articulo[0].idarticulo);
                    $("#pdescuento").val('0');
                    $("#pcantidad").focus();
                }
            },

        });
        return false;
    }


    function tipoFactura() {

        datosPersona = document.getElementById('idcliente').value.split('_');
        $("#idcliente2").val(datosPersona[0]);

        if ($("#tipo_comprobante").val() != 'Factura C') {

            if (datosPersona[1] == 'Resp. Inscripto') {
                $("#tipo_comprobante").val('Factura A');
                $("#idcliente2").val(datosPersona[0]);


            } else {
                $("#tipo_comprobante").val('Factura B');
                $("#idcliente2").val(datosPersona[0]);

            }
        }
    }

    function imprimir() {


        mywindow = window.open("", "ventana1", "height=300,width=300");
        mywindow.document.write('<html><head><title>' + 'Ticket' + '</title>');
        mywindow.document.write('</head><body>');
        mywindow.document.write('<div style="text-align:center; font-family: Arial">');
        mywindow.document.write('<b style="font-size: 25px">' + 'OBRAN' + '</b>');
        mywindow.document.write('<br>');
        mywindow.document.write('Alimentos Congelados');
        mywindow.document.write('<br>');
        mywindow.document.write('<span style="font-size: 12px">Fecha: ' + document.getElementById('date').innerHTML + '</span>');
        mywindow.document.write('<hr>');
        mywindow.document.write('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">' + document.getElementById('table').innerHTML + '</div>');
        mywindow.document.write('<hr>');
        mywindow.document.write('</div><br>');
        mywindow.document.write('<span style="font-size: 15px">Recargo Credito: $' + $("#monto_porcentaje").val() + ' (' + $("#porcentaje_credito").val() + ')% </span><br>');
        mywindow.document.write('<span style="font-size: 15px">Descuento: ' + $("#descuento_total").val() + '% </span><br>');
        mywindow.document.write('<hr>');
        mywindow.document.write('<b style="font-size: 20px; font-family: Arial">Total: $' + document.getElementById('totalrecargo2').innerHTML + '</b>');
        mywindow.document.write('</div>');
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.window.print();

        function close() {

            mywindow.close();

        }
        setTimeout(close(), 2000);
    }
</script>

@endpush
@endsection