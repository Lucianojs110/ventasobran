@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">

                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <h3>Listado de Devoluciones</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table">
                            <table id="dev" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Fecha de Devolucion</th>
                                    <th>Número de Devolución</th>
                                    <th>Número de Factura</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody>
                                </tbody>
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
        $('#dev').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            order: [[2, "desc"]],
            ajax: {
                url: "{{route('devolucion.tabla')}}",
                type: 'GET'
            },
            columns: [
                {data: 'fecha', name: 'fecha'},
                {data: 'num_comprobante', name: 'num_comprobante'},
                {data: 'num_factura', name: 'num_factura'},
                {data: 'opcion', name: 'opcion', orderable: false, searchable: false}
            ],
            "language": {
                "url": "{{URL::to('/')}}/admin/Spanish.json"
            }

        });
        $('#btnFiterSubmitSearch').click(function () {
            $('#ven').DataTable().ajax.reload();
        });
    </script>
@stop