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
    background: url('{{ asset('imagenes/loading.gif') }}') 50% 50% no-repeat rgb(249, 249, 249);
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
 <!-- inicio ticket -->

<div class="col-md-6" id="ticket" >
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
          Cuit: {{ $sucursal->cuit }}<br>
          Ingresos Brutos: {{ $sucursal->ingresos_brutos }}<br>
          @if($sucursal->impuesto == 11)
            Iva: Monotributo <br>
          @else
            Iva: Responsable Inscripto <br>
          @endif

          Direccion: {{ $sucursal->direccion }}<br>
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
  <div style="text-align:left; font-size:8px; font-family: Arial; width: 100%">
    <table class="table bordered" id="detalle">
      <thead>
        <tr>
          <th style="width: 20%; text-align: left; font-size:11px; font-family: Arial">Cant.</th>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">Art</th>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">P. Unit</th>
          <th style="width: 100%; text-align: left; font-size:11px; font-family: Arial">STotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($venta->detalles as $det)
          <tr>
            <td style="width: 20%; text-align: left; font-size:11px; font-family: Arial">{{ $det->cantidad }}x</td>
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

          <th style="text-align:left"><b>Descuento</b></th>
          <th class="text-left" >{{ $venta->porcentaje_descuento }}%</th>
        </tr>
        <tr>    
          <th style="text-align:left"><b> Total</b></th>
          <th class="text-left" id="total">${{ $venta->total_venta }}</th>
        </tr>
      </tfoot>
    </table>
    
 
  </div>
</div>

  <div class="row">

    <div class="col-sm-12">
      <div id="cae">
       
            @if($venta->cae!=null)
            <div class="col-sm-6" >

                  <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{ $venta->codigoQr }}">

             </div>
             <div class="col-sm-6" >
                  <b>N° CAE: </b> {{ $venta->cae }} <br>
                  Fecha Vto. CAE:
                  {{ $newDate = date("d-m-Y", strtotime($venta->vtocae)) }}
              </div>
            @endif
        
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
             
            @endif
            <input type="hidden" class="form-control" id="idventa" value="{{ $venta->idventa }}">
          </div>
        </div>
      </div>
    </div>



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
    setTimeout(function(){ 
    
    mywindow = window.open("", "ventana1", "height=300,width=300");
    mywindow.document.write('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">' + document.getElementById('ticket').innerHTML + '</div>');
    mywindow.document.write('<div style="width: 100%; text-align: center; font-size:10; font-family: Arial">');
    mywindow.document.write('<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=48784848484848"><br>');
    mywindow.document.write('CAE: {{ $venta->cae }} <br> Vto. CAE: {{ $newDate = date("d-m-Y", strtotime($venta->vtocae)) }}<br>');
    mywindow.document.write('</div>');
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.window.print();
    function close() {
      mywindow.close();
      }setTimeout(close(), 1500);
    }, 1000);
    
  }
</script>
@endsection
@stop