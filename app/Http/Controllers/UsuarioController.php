<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use SisVentaNew\Http\Requests\UsuarioFormRequest;
use SisVentaNew\User;
use SisVentaNew\Sucursal;
use Yajra\DataTables\Facades\DataTables;

class UsuarioController extends Controller
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
        $user = User::where('estado','Activo')->get();

        $roles = DB::table('roles')->select('id', 'name')->get();
        $user_roles = DB::table('rols_user')->select('id', 'user_id', 'role_id')->get();
        $sucursales = Sucursal::where('estado', 'Activo')->get();
        $sucursalesUser = DB::table('sucursals_users')->orderBy('sucursal_id')->get(); 

        return view('usuarios.index', compact(['user', 'roles', 'user_roles', 'sucursales', 'sucursalesUser']));
    }

    public function tabla()
    {
        $users = User::where('estado','Activo')->get();


        return Datatables::of($users)
            ->addColumn('opcion', function ($ar) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-borrar-' . $ar->id . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Borrar usuario: '.$ar->name.'"  class="fa fa-trash"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-editar-' . $ar->id . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar usuario: '.$ar->name.'"  class="fa fa-edit"></i></a>
                        </div>
                ';
            })
            ->editColumn('name', function ($us) {
                return $us->name.' '.$us->apellido;
            })
            ->rawColumns(['opcion'])
            ->make(true);
    }

    public function store (Request $request)
    {
        $vali = User::where('email', $request->email)->first();

        if($vali != null){
            toastr()->error('Error, email ya existente',);
            return Redirect::back();
        }

        $user = New User();
        $user->name = $request->name;
        $user->apellido = $request->apellido;
        $user->estado = 'Activo';
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        
        DB::table('rols_user')->insert(['user_id' => $user->id, 'role_id' => $request->rol]);

        foreach($request->suc as $suc){
            DB::table('sucursals_users')->insert(['user_id' => $user->id, 'sucursal_id' => $suc]);
        }

        toastr()->success('Usuario agregado correctamente!', ''.$request->name);
        return Redirect::back();
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != null)
        {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        DB::table('rols_user')->where('user_id', $id)->update(['role_id' => $request->rol]);

        DB::table('sucursals_users')->where('user_id', $id)->delete();

        foreach($request->suc as $suc){
            DB::table('sucursals_users')->insert(['user_id' => $id, 'sucursal_id' => $suc]);
        }
                
        toastr()->info('Su usuario se ha editado correctamente!', ''.$request->name);
        return Redirect::back();
        
    }

    public function delete($id)
    {
        $users = User::find($id);
        $users->estado = 'Desactivo';
        $users->save();

        DB::table('rols_user')->where('user_id', $id)->delete();

        DB::table('sucursals_user')->where('user_id', $id)->delete();

        toastr()->error('Su usuario se ha borrado correctamente!', ''.$users->name);
        return redirect()->route('login');

    }
}
