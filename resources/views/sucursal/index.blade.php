@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <h3>Listado de Sucursales
                            <button  data-toggle="modal" data-target="#agregar" class="btn btn-xs btn-success"><i data-toggle="tooltip" title="Agregar Sucursal" class="fa fa-plus-circle"></i></button>
                        </h3>
                    </div>
                </div>
                @include('sucursal.modal-agregar')
                @foreach($sucursales as $sucursal)
                    @include('sucursal.modal-borrar')
                    @include('sucursal.modal-editar')
                    
                @endforeach
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="sucursales" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Ciudad</th>
                                    <th>Cuit</th>
                                    <th>Ingresos Brutos</th>
                                    <th>Telefono</th>
                                    <th>Correo</th>
                                    <th>Impuesto</th>
                                    <th>Opciones</th>
                                </tr>
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
        });

        $('#sucursales').DataTable({
            processing: true,
            serverSide: true,
            'iDisplayLength': 10,
            ajax: "{{route('sucursal.tabla')}}",
            columns: [
                {data: 'nombre', name: 'nombre'},
                {data: 'direccion', name: 'direccion'},
                {data: 'ciudad', name: 'ciudad'},
                {data: 'cuit', name: 'cuit'},
                {data: 'ingresos_brutos', name: 'ingresos brutos'},
                {data: 'telefono', name: 'telefono'},
                {data: 'email', name: 'correo'},
                {data: 'impuesto', name: 'impuesto'},
                {data: 'opcion', name: 'opcion', orderable: false, searchable: false}
            ],
            "language": {
                "url": "{{URL::to('/')}}/admin/Spanish.json"
            }
        });
    </script>
@stop


