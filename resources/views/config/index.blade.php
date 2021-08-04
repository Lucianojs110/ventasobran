@extends('layouts.app')
@section('content')
<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border" >
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          <h3>Configuración Del Sistema <a class="btn btn-primary btn-xs"
              href="{{ URL::action('ConfigController@edit', $config->idconfig) }}"><i
                class="fa fa-edit"></i> Editar</a></h3>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
              <h4><b> Nombre Negocio:</b> {{ $config->nombre }}</h4>
            </div>
            <div class="form-group">
              <h4><b> Lema:</b> {{ $config->lema }}</h4>
            </div>
            <div class="form-group">
              <h4><b> Razon Social:</b>  {{ $config->razon_social }}</h4>
            </div>
            <div class="form-group">
              <h4><b> Cuit: </b> {{ $config->dni }}</h4>
            </div>
            <div class="form-group">
              <h4><b> Ingresos Brutos: </b> {{ $config->ingresos_brutos }}</h4>
            </div>
            <div class="form-group">
              <h4><b> Teléfono: </b> {{ $config->telefono }}</h4>
            </div>
           
         
          </div>
          

          <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
              <h4><b> Direccion:</b> {{ $config->direccion }}</h4>
            </div>
            <div class="form-group">
              <h4><b>Condicion IVA:</b>
                @if($config->impuesto==6)
                  Resp. Inscripto
                @else
                  Monotributo
                @endif
              </h4>
            </div>
            <div class="form-group">
              <h4><b>Punto de venta:</b> {{ $config->punto_venta }}</h4>
            </div>
            <div class="form-group">
              <h4><b>Correo:</b> {{ $config->correo }}</h4>
            </div>
            <div class="form-group">
              <h4><b>Página:</b> {{ $config->campo2 }}</h4>
            </div>
            <div class="form-group">
              <h4><b>Sincronizado con WooCommerce:</b> 
              @if($config->sincronizar==1)
                  Si
              @else
                  No
              @endif        
              </h4>
            </div>
          </div>


          <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
           
              <img src="{{ asset('imagenes/config/'.$config->imagen) }}"
                style="background-color:white" height="300px" width="300px" class="img-thumbnail">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection