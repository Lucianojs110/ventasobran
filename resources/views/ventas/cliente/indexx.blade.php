@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">

                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <h3>Listado de Clientes
                            <button  data-toggle="modal" data-target="#modal-agregar-cliente" class="btn btn-xs btn-success"><i data-toggle="tooltip" title="Agregar Cliente" class="fa fa-plus-circle"></i></button>
                            <a href="{{route('pdf.cliente')}}" class="btn btn-primary btn-xs" ><i data-toggle="tooltip" title="Imprimir Clientes"  class="fa fa-fw fa-print"></i></a>
                        </h3>
                    </div>
                </div>
                @include('ventas.cliente.modal-agregar')
                @foreach($personas as $cli)
                    @include('ventas.cliente.modal-editar')
                    @include('ventas.cliente.modal-borrar')
                    @include('ventas.cliente.modal-show')
                @endforeach
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table">
                            <table id="cli" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                <th>Nombre</th>
                                <th>Documentp</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Opciones</th>
                                </thead>
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
        
            $("#num_documento").on('change keyup paste', function () {
               
                if($(this).val().length == 11) {

               $.ajax({
               headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
               type: "POST",
               url: "{{route('consultarcuit')}}",
               dataType: "json",
               beforeSend: function() {
                document.getElementById("loading").style.display="block";
               },
               data: {

             num_documento: $("#num_documento").val(),
                },
           
             success: function(data) {
             console.log(data);
             document.getElementById("loading").style.display="none";
             if(data.persona == null){
                toastr.warning('Cuit no Valido')
                $("#nombre").val('');
                $("#direccion").val('');
                $("#tipo").val('Consumidor Final');
             }
             
             else if (data.persona.datosGenerales == null ){
                    $("#tipo").val('Consumidor Final');
                    $("#nombre").val(data.persona.errorConstancia.apellido);
                    $("#direccion").val('');
                    $("#tipo").val('Consumidor Final');
                    toastr.warning(data.persona.errorConstancia.error)
                 }else
                    {
                     if  (data.persona.datosMonotributo != null)
                     {
                          $("#nombre").val(data.persona.datosGenerales.razonSocial);
                          $("#tipo").val('Monotributo');
                          $("#direccion").val(data.persona.datosGenerales.domicilioFiscal.direccion+' - '+data.persona.datosGenerales.domicilioFiscal.localidad+' - '+data.persona.datosGenerales.domicilioFiscal.descripcionProvincia);
                          toastr.success('Cuit Valido')
                     }
                     
                     $("#nombre").val(data.persona.datosGenerales.apellido+' '+data.persona.datosGenerales.nombre);
                     $("#direccion").val(data.persona.datosGenerales.domicilioFiscal.direccion+' - '+data.persona.datosGenerales.domicilioFiscal.localidad+' - '+data.persona.datosGenerales.domicilioFiscal.descripcionProvincia);
                     toastr.success('Cuit Valido')
                    
                    if  (data.persona.datosRegimenGeneral.impuesto[1].idImpuesto == '30')
                        {
                             $("#nombre").val(data.persona.datosGenerales.razonSocial);
                             $("#tipo").val('Resp. Inscripto');
                             $("#direccion").val(data.persona.datosGenerales.domicilioFiscal.direccion+' - '+data.persona.datosGenerales.domicilioFiscal.localidad+' - '+data.persona.datosGenerales.domicilioFiscal.descripcionProvincia);
                             toastr.success('Cuit Valido')
                        }
                        else if  (data.persona.datosRegimenGeneral.impuesto[0].idImpuesto == '32')
                        {
                             $("#nombre").val(data.persona.datosGenerales.razonSocial);
                             $("#tipo").val('IVA Exento');
                             $("#direccion").val(data.persona.datosGenerales.domicilioFiscal.direccion+' - '+data.persona.datosGenerales.domicilioFiscal.localidad+' - '+data.persona.datosGenerales.domicilioFiscal.descripcionProvincia);
                             toastr.success('Cuit Valido')
                        }
                     

                        
                    };
    
            },
             error: function(){
             toastr.warning("Ocurrio un problema")
            }
           });
        return false;


 
           }; 

       });
 
        
        });



        $('#cli').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            order: [[0, "desc"]],
            ajax: "{{route('cliente.tabla')}}",
            columns: [
                {data: 'nombre', name: 'nombre'},
                {data: 'documento', name: 'documento'},
                {data: 'telefono', name: 'telefono'},
                {data: 'email', name: 'email'},
                {data: 'tipo_contribuyente', name: 'tipo_contribuyente'},
                {data: 'opcion', name: 'opcion', orderable: false, searchable: false}
            ],
            "language": {
                "url": "{{URL::to('/')}}/admin/Spanish.json"
            }
        });

    </script>
@stop



