@extends('layouts.app')
@section('content')
<style>
  .loader {
    display: none;
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('{{ asset('imagenes/loading2.gif') }}') 50% 50% no-repeat rgb(249, 249, 249);
    opacity: .8;
  }

  .cargando {
    position: fixed;
    font-size: 25px;
    left: 30%;
    top: 60%;
    width: 100%;
    height: 100%;
    z-index: 9998;

  }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="loader" id="loader">
  <div class="cargando"><b> Se esta registrando su factura. Aguarde un momento... </b></div>
</div>
<div class="container" style=" padding:20px;">
  <div class="col-md-3">
  </div>
  <div class="col-md-7" style="background-color: #fff; margin-left:30px;  padding:20px">

    <table>
      <tbody>
        <tr>
          <th WIDTH="400" HEIGHT="40" style="border: 1px solid; text-align: center">

            <img src="{{ asset('imagenes/config/'.$config->imagen) }}" height="80%"
              width="45%"><br>
            <span>de: {{ $config->razon_social }}</span>
          </th>
          <th WIDTH="100" style="border: 1px solid; text-align: center">

            @if($venta->tipo_comprobante=='Factura C')
              <span style="font-size: 50px; font-family: sans-serif">C</span><br>
              <span>cod. 11</span>
            @elseif($venta->tipo_comprobante=='Factura A')
              <span style="font-size: 50px; font-family: sans-serif">A</span><br>
              <span>cod. 11</span>
            @else
              <span style="font-size: 50px; font-family: sans-serif">B</span><br>
              <span>cod. 06</span>
            @endif

          <th WIDTH="400" style="border: 1px solid; text-align: center">
            <div id="numero">
              <span style="font-size: 20px; font-family: sans-serif">FACTURA </span><br>
              <span>Nº: {{ $venta->num_comprobante }}</span><br>
              Fecha: {{ $newDate = date("d-m-Y", strtotime($venta->fecha_hora)) }}

            </div>
          </th>
        </tr>
      </tbody>

    </table>
    <table>
      <tbody>
        <tr style="border: 1px solid">
          <th WIDTH="500" HEIGHT="50" style="padding: 10px; font-family: sans-serif">
            Direccion: {{ $config->direccion }}<br>
            @if($config->impuesto == 11)
              Iva: Monotributo <br>
            @else
              Iva: Responsable Inscripto <br>
            @endif
            Cuit: {{ $config->dni }}<br>
          </th>
          <th WIDTH="500" style=" padding: 10px; font-family: sans-serif">
            Ingresos Brutos: {{ $config->ingresos_brutos }}<br>
            Telefono: {{ $config->telefono }}<br>
            Email: {{ $config->correo }}<br>
          </th>

        <tr style="border: 1px solid">

          <th WIDTH="500" HEIGHT="50" style="padding: 10px; font-family: sans-serif">
            Cliente: {{ $venta->cliente->nombre }} <br>
            @if($venta->cliente->num_documento=='0')
              Cuit:<br>
            @else
              Cuit: {{ $venta->cliente->num_documento }} <br>
            @endif
            Cond. IVA: <br>

          </th>
          <th WIDTH="500" style=" padding: 10px; font-family: sans-serif">
            Domicilio: {{ $venta->cliente->direccion }} <br>
            Telefono: {{ $venta->cliente->telefono }} <br>
            Cond. Venta: CONTADO
          </th>
  </div>
  </tr>
  </tbody>
  </table>



  <table style="font-family: sans-serif; border: 1px solid; padding: 10px" id="detalle">
    <thead>
      <tr>
        <th style="padding: 3px; border: 1px solid; background-color:#CCD1D1">Articulo</th>
        <th style="padding: 3px; border: 1px solid; background-color:#CCD1D1">Cant.</th>
        <th style="padding: 3px; border: 1px solid; background-color:#CCD1D1">P. Unit.</th>
        <th style="padding: 3px; border: 1px solid; background-color:#CCD1D1">Desc.</th>
        <th style="padding: 3px; border: 1px solid; background-color:#CCD1D1">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($venta->detalles as $det)
        <tr>
          <td style="width: 60%; text-align: left; padding: 3px;" class="text-right">{{ $det->articulo->nombre }}
          </td>
          <td style="width: 15%; text-align: left; padding: 3px;" class="text-right">{{ $det->cantidad }}</td>
          <td style="width: 15%; text-align: left; padding: 3px;" class="text-right">{{ $det->precio_venta }}</td>
          <td style="width: 15%; text-align: left; padding: 3px;" class="text-right">{{ $det->descuento }}</td>
          <td style="width: 15%; text-align: left">
            {{ $det->cantidad*$det->precio_venta-$det->descuento }}</td>
        </tr>
      @endforeach
      @if($venta->monto_porcentaje != 0)
        <tr>

          <td>Monto de interés por crédito</td>
          <td class="text-derecha">1</td>
          <td class="text-derecha">${{ $venta->monto_porcentaje }}
            ({{ $venta->porcentaje_credito }}%)
          </td>
          <td class="text-derecha">0.00</td>
          <td class="text-derecha">${{ $venta->monto_porcentaje }}</td>
        </tr>
      @endif
    </tbody>

  </table>
  <h3>Importe total: ${{ $venta->total_venta }}</h3>
  <br>


  <br>

  <div class="row">

    <div class="col-sm-12">
      <div id="cae">
        <table>
          <tbody>
            @if($venta->cae!=null)
              <tr>
                <th WIDTH="250" HEIGHT="100">

                  <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl={{ $venta->codigoQr }}">

                </th>
                <th WIDTH="600" HEIGHT="100">
                  <img src="{{ asset('imagenes/config/logo_afip.png') }}" height="40%"
                    width="40%"><br>
                  <span style="font-size: 15px">Comprobante Autorizado</span><br>
                  <span style="font-size: 10px">Esta administración federal no se responsabiliza por los datos
                    ingresados en el detalle de la operación</span>
                </th>
                <th WIDTH="300" HEIGHT="100">
                  <b>N° CAE: </b> {{ $venta->vtocae }} <br>
                  Fecha Vto. CAE:
                  {{ $newDate = date("d-m-Y", strtotime($venta->vtocae)) }}
                </th>
            @endif
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <div class="col-md-3">
    </div>
    <div class="row" id="noimpr">
      <div id="reload">
        <div class="col-sm-12">
          <div class="col-sm-6" id="btncae">
            @if($venta->cae==null)
              <button id="btn-sol-cae" class="btn btn-primary btn-xs">Solicitar CAE</button>
            @endif
          </div>
          <div class="col-sm-6" id="btnimp" style="text-align:right">
            @if($venta->cae!=null)
              <button type="button" class="btn btn-primary btn-xs" onclick="javascript:window.print()"><i
                  class="fa fa-print"></i>Imprimir</button>
              <button type="button" id="tprint" class="btn btn-primary btn-xs"><i class="fa fa-print"></i> Imprimir
                ticket</button>
            @endif
            <input type="hidden" class="form-control" id="idventa" value="{{ $venta->idventa }}">
          </div>
        </div>
      </div>
    </div>



  </div>
</div>
</div>

<!-- inicio ticket -->

<div class="col-md-6" id="ticket" style="display: none">
  <table>
    <tbody>
      <tr>
        <div style="text-align:center; font-family: Arial">
          <b> {{ $config->nombre }} </b> <br>
          {{ $config->lema }} <br>
          <hr>
        </div>
        <div style="text-align:left; font-size:12; font-family: Arial">
          Razon Social: {{ $config->razon_social }}<br>
          Cuit: {{ $config->dni }}<br>
          Ingresos Brutos: {{ $config->ingresos_brutos }}<br>
          @if($config->impuesto == 11)
            Iva: Monotributo <br>
          @else
            Iva: Responsable Inscripto <br>
          @endif

          Direccion: {{ $config->direccion }}<br>
          @if($venta->tipo_comprobante=='Factura C')
            Factura: C<br>

          @elseif($venta->tipo_comprobante=='Factura A')
            Factura: A<br>

          @else
            Factura: B<br>
          @endif
          Nº: {{ $venta->num_comprobante }}<br>
          Fecha: {{ $newDate = date("d-m-Y", strtotime($venta->fecha_hora)) }}<br>
        </div>
        <div style="text-align:center; font-family: Arial">
          <hr>
        </div>
        <div style="text-align:left; font-size:12; font-family: Arial">
          Cliente: {{ $venta->cliente->nombre }} <br>
          @if($venta->cliente->num_documento=='0')
          @else
            Cuit: {{ $venta->cliente->num_documento }} <br>
          @endif
          Cond. Venta: CONTADO
        </div>
      </tr>
</div>


</tbody>
<hr>
</table>
<div>
  <div style="text-align:left; font-size:8px; font-family: Arial">
    <table class="table bordered" id="detalle">
      <thead>
        <tr>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">Cant.</th>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">Art</th>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">P. Unit</th>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">STotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($venta->detalles as $det)
          <tr>
            <td style="width: 100%; text-align: left; font-size:11px; font-family: Arial">{{ $det->cantidad }}x</td>
            <td style="width: 100%; text-align: left; font-size:11px; font-family: Arial">
              {{ $det->articulo->nombre }}</td>
            <td style="width: 100%; text-align: left; font-size:11px; font-family: Arial">${{ $det->precio_venta }}
            </td>
            <td style="width: 100%; text-align: left; font-size:11px; font-family: Arial">
              ${{ $det->cantidad*$det->precio_venta-$det->descuento }}</td>
          </tr>
        @endforeach
      </tbody>

      <tfoot>

        <tr>

          <th class="text-left"><b> Total</b></th>
          <th class="text-left" id="total">${{ $venta->total_venta }}</th>
        </tr>
      </tfoot>
    </table>
    <hr>

    <div style="width: 100%; text-align: center; font-size:10; font-family: Arial">
      @if($venta->cae!=null)
        <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl={{ $venta->codigoQr }}"><br>
        CAE: {{ $venta->cae }} <br>
        Vto. CAE: {{ $newDate = date("d-m-Y", strtotime($venta->vtocae)) }}<br>
      @endif

    </div>
  </div>
</div>


<!-- fin tiket -->

@section('js')
<script>
  $(document).ready(function () {
    $("#btn-sol-cae").click(function () {
      solicitar_cae()
    });

    $('#tprint').click(function () {
      imprimir();
    });
  });

  function solicitar_cae() {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: "POST",
      url: "{{ route('solicitarcae') }}",
      dataType: "json",
      beforeSend: function () {
        document.getElementById("loader").style.display = "block";
      },
      data: {
        idventa: $("#idventa").val(),
      },
      success: function (data) {
        document.getElementById("loader").style.display = "none";
        $("#cae").load(" #cae");
        $("#numero").load(" #numero");
        $("#reload").load(" #reload");
        toastr.success("Comprobante registrado")
        console.log(data)
      },
      error: function (data) {
        toastr.warning("Algo ha salido mal al conectarse al webserver")
        alert('No se realizo la operacion. Respuesta del servidor: ' + data.responseJSON.message)
      },

    });
    return false;
  }


  function imprimir() {


    mywindow = window.open("", "ventana1", "height=300,width=300");


    mywindow.document.write('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">' + document
      .getElementById('ticket').innerHTML + '</div>');

    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/


    mywindow.window.print();

    function close() {

      mywindow.close();

    }
    setTimeout(close(), 1500);



  }
</script>
@endsection
@stop