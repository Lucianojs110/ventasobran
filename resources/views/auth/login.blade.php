@extends('layouts.app')
@section('content')
    <div class="login-box">
         <div class="login-box-msg">
            <h2><b>OBRAN</b></h2>
            <h3>Alimentos Congelados</h3>
            <div class="login-box-body">
                <p class="login-box-msg">Ingresa tus datos para entrar al Sistema</p>
                        <form id="login-form" action="{{ route('login') }}" method="POST" role="form" novalidate="">
                        {{ csrf_field() }}

                        @if(Session::has('message'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{Session::get('message')}}
                        </div>
                       @endif
                        
                            <div class="has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">Correo</label>
                                <input type="email" for="email" class="form-control" name="email" id="email" placeholder="Ingrese su correo " required value="{{ old('email') }}"  >
                                @if ($errors->has('email'))
                                    <span class="has-error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="has-feedback">
                                <label class="control-label" for="inputError1" for="password">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Tu Contraseña" required>
                                @if ($errors->has('password'))
                                    <span class="has-error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>


                        <div class="form-group">
                            <label>Sucursal</label>
                            <select name="sucursal" class="form-control">
                                @foreach ($sucursal as $sucursales)
                                    <option value="{{$sucursales->id}}">{{$sucursales->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                            <div class="has-feedback">
                                  <label>
                                    <input  name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                     Recordarme  </label>
                            </div>
                            <div class="has-feedback">
                                <button type="submit" class="btn btn-block btn-primary">Entrar</button>
                            </div>
                           
                        </form>
            </div>
        </div>
    </div>
@endsection
