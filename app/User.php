<?php namespace SisVentaNew;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
  use Authenticatable, CanResetPassword;

  protected $throwValidationExceptions = true;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'email','estado','apellido', 'password'];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['password', 'remember_token'];

  protected $hashable = ['password'];

  public function rols(){
    return $this->belongsToMany(Role::class);
  }

  public function assignRole($role){
    $this->rols()->sync($role, false);
  }

  public function hasRole($role){
    $userId = auth()->user()->id;
    $rol = DB::table('roles')->where('name', $role)->first();

    $userRole = DB::table('rols_user')->where('user_id', $userId)->where('role_id', $rol->id)->first();

    if($userRole != null){
      return true;
    }
  }
}
