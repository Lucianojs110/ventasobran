@extends('layouts.app')
@section('content')
<style>
    .rect-checkbox {
        float: left;
        margin-left: 130px;
    }

    .span {
        margin-left: -180px;
    }
</style>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h3>Nueva Ingreso</h3>
                    @if(count($errors)>0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            {!! Form::open(['route' => 'ingreso.store', 'method'=>'POST', 'id'=>'cre', 'autocomplete' => 'off'])!!}
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="proveedor">Proveedor</label>
                        <select autofocus name="idproveedor" required class="form-control selectpicker" id="idproveedor" data-live-search="true">
                            @foreach($personas as $persona)
                            <option value="{{ $persona->idpersona }}">{{ $persona->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Tipo Comprobante:</label>
                        <input type="text" readonly value="Ingreso" name="tipo_comprobante" class="form-control" placeholder="Numero Comprobante">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="num_comprobante">Número Comprobante:</label>
                        @if($ing == '1')
                        <input type="text" readonly value="0-0" name="num_comprobante" class="form-control" placeholder="Numero Comprobante">
                        @else
                        <input type="text" readonly value="0-{{ $ing->idingreso }}" name="num_comprobante" class="form-control" placeholder="Numero Comprobante">
                        @endif
                    </div>
                </div>
            </div>
            <div class="">
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
                                    <input type="number" step="0.01" name="pcantidad" id="pcantidad" class="form-control" placeholder="Cantidad">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label for="precio_compra">Precio Compra</label>
                                    <input type="number" name="pprecio_compra" id="pprecio_compra" class="form-control" placeholder="Precio Compra">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label for="precio_venta">Precio Venta</label>
                                    <input type="number" name="pprecio_venta" id="pprecio_venta" class="form-control" placeholder="Precio Venta">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <label for="">
                                        <hr>
                                    </label>
                                    <button type="button" id="bt-add" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Agregar</button>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table">
                            <table id="detalles" class="table">
                                <thead style="background-color: #A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículos</th>
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Subtotal</th>
                                </thead>
                                <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <h4 id="total">$ 0.00</h4><input type="hidden" name="total_ingreso" id="total_ingreso">
                                    </th>
                                    </th>
                                </tfoot>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="guardar">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                        <button id="cre" class="btn btn-primary pull-left btn-xs btn-flat" type="submit"><i class="fa fa-save"></i> Guardar </button>
                        <button class="btn btn-danger pull-right btn-xs btn-flat" type="reset"><i class="fa fa-window-close"></i> Cancelar</button>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</section>
@section('js')
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#bt-add').click(function() {
            agregar();
        });

        $("#cre").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });

        $("#pcodigo").keyup(function() {


            if ($("#pcodigo").val().length > 7) {
                consultar_codigo();

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

    function agregar() {
        codigo = $("#pcodigo").val();
        idarticulo = $("#pidarticulo").val();
        articulo = $("#pnombre").val();
        cantidad = $("#pcantidad").val();
        precio_compra = $("#pprecio_compra").val();
        precio_venta = $("#pprecio_venta").val();


        if (idarticulo != "" && cantidad != "" && cantidad > 0 && precio_compra != "" && precio_venta != "") {
            subtotal[cont] = (cantidad * precio_compra);
            total = total + subtotal[cont];
            var fila = '<tr class="selected" id="fila' + cont +
                '"><td><button type="button" class="btn btn-danger btn-xs" onclick="eliminar(' + cont +
                ');">X</button></td><td><input type="hidden" name="codigo[]" value="' + codigo + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' +
                articulo + '</td><td><input readonly type="number" name="cantidad[]" value="' + cantidad +
                '"></td><td><input readonly type="number" name="precio_compra[]" value="' + precio_compra +
                '"></td><td><input readonly type="number" name="precio_venta[]" value="' + precio_venta +
                '"></td><td>' + subtotal[cont] + '</td></tr>';
            cont++;
            limpiar();
            $('#total').html("$ " + total);
            evaluar();
            $('#detalles').append(fila);
            $('#total_ingreso').val(total);

        } else {
            alert("Error al ingresar el detalle del ingreso, revise los datos del artículo");
        }
    }

    function limpiar() {
        $('#pcantidad').val("");
        $('#pnombre').val("");
        $('#pcodigo').val("");
        $('#pprecio_venta').val("");
        $('#pprecio_compra').val("");
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
        total = total - subtotal[index];
        $("#total").html("$ " + total);
        $("#fila" + index).remove();
        evaluar();
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
                $('#pstock').val(data.articulo[0].stock);
                $('#pnombre').val(data.articulo[0].nombre);
                $('#pprecio_venta').val(data.articulo[0].precio_venta);
                $('#pidarticulo').val(data.articulo[0].idarticulo);
                $("#pdescuento").val('0');
            },

        });
        return false;
    }
</script>
@endpush
@endsection