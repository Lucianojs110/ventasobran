<div class="modal fade modal-info" id="modal-editar-{{$use->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Editar usuario: {{$use->name}}</h5>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
                {!!Form::model($use,['route'=>['usuarios.update', $use->id] , 'id'=>'edit-'.$use->id.'', 'method'=>'put', 'enctype'=>'multipart/form-data'])!!}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Nombre</label>
                        <input  value="{{$use->name}}" type="text" name="name" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="">Apellido</label>
                        <input required value="{{$use->apellido}}" type="text" name="apellido" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="">Correo</label>
                        <input value="{{$use->email}}" type="email" name="email" class="form-control">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="fuente" for="documento">Cambiar contraseña (sino desea cambiarla, no modificar
                            campo)</label>
                        <input type="password" class="form-control" name="password"/>
                    </div>

                    <div class="py-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="text-gray-700" for="rol">
                        Rol:
                        <select name="rol" class="form-control">
                            
                            @foreach($user_roles as $usrol)
                                @if($usrol->user_id == $use->id)

                                    @foreach($roles as $rols)
                                        @if($usrol->role_id == $rols->id)
                                            <option value="{{$rols->id}}" selected> {{$rols->name}} </option>
                                        @else
                                            <option value="{{$rols->id}}"> {{$rols->name}} </option>
                                        @endif
                                    @endforeach

                                @endif

                            @endforeach
                            
                            
                        </select>

                    </div>

                </div>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" form="edit-{{$use->id}}" class="btn btn-outline  btn-xs"><i
                            class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>