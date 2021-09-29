@extends('layouts.app')
@section('content')
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border ">
            
        
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                </div>
                {!!Form::model($config,['route'=>['configuracion.update', $config->idconfig] , 'method'=>'PATCH',
                'files'=>'true'] )!!}
                {{ Form::token() }}
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
                <div class="container border" style=" border-radius:5px; border:1px solid #BFC9CA "> 
                <h4>Configuración Del Sistema </h4>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Razon Social</label>
                            <input type="text" required value="{{ $config->razon_social }}" name="razonSocial"
                                placeholder="Razon social contribuyente" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Nombre Negocio</label>
                            <input type="text" required value="{{ $config->nombre }}" name="nombre"
                                placeholder="Nombre del negocio" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Lema</label>
                            <input type="text" value="{{ $config->lema }}" name="lema"
                                placeholder="Lema del Negocio" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Condicion frente al Iva</label>
                            <select name="impuesto" class="form-control">
                                @if($config->impuesto == '11')
                                    <option selected value="11">Monotributo</option>
                                    <option value="6">Resp. Inscripto</option>
                                @else
                                    <option value="6">Resp. Inscripto</option>
                                    <option selected value="11">Monotributo</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Punto venta</label>
                            <input type="text" required value="{{ $config->punto_venta }}" name="punto"
                                placeholder="punto de venta" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>CUIT</label>
                            <input type="text" value="{{ $config->dni }}" name="dni" placeholder="DNI del negocio"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Ingresos Brutos</label>
                            <input type="text" required value="{{ $config->ingresos_brutos}}" name="ingresos_brutos"
                                placeholder="Num. Ingresos Brutos" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" required value="{{ $config->telefono }}" name="telefono"
                                placeholder="Teléfono del negocio" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Correo del Negocio</label>
                            <input type="text" value="{{ $config->correo }}" name="correo"
                                placeholder="Correo del negocio" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Página del Negocio</label>
                            <input type="text" value="{{ $config->campo2 }}" name="pagina"
                                placeholder="Página web del negocio" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Dirección del Negocio</label>
                            <input type="text" value="{{ $config->direccion }}" name="direccion"
                                placeholder="Dirección del Negocio" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Menu</label>
                            <select name="menu_mini" class="form-control">
                                @if($config->menu_mini == '1')
                                    <option selected value="1">Min.</option>
                                    <option value="2">Max.</option>
                                @else
                                    <option value="1">Min.</option>
                                    <option selected value="2">Max.</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    

        </div>
        <br>
       
   
        <br>  
        <div class="container border" style=" border-radius:5px; border:1px solid #BFC9CA "> 
        <h4>Imagen de los comprobantes</h4>
        <div class="container col-lg-6">      
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Logo del negocio</label>
                            <input type="file" name="imagen" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">

                            @if(($config->imagen)!="")
                                <img src="{{ asset('imagenes/config/'.$config->imagen) }}"
                                    height="200px" width="200px" class="img-thumbnail" style="background-color:white">
                            @endif
                        </div>
                    </div>

            </div>     
            </div>      
            <br>
             <div class="container " >         

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="reset" class="btn btn-danger btn-xs" data-dismiss="modal"> <iclass="fa fa-window-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-success  btn-xs"> <i class="fa fa-save"></i> Guardar</button>
                        <a class="btn btn-primary btn-xs"href="{{ route('configuracion') }}"><i class="fa fa-arrow-left"></i> Volver</a>
                    </div>
                </div>
                </div>
             
                {!!Form::close()!!}

            </div>
        </div>
        
    </div>
</section>
@endsection