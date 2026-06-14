<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuAdminController;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use App\Models\Role;
use App\Models\Permiso;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAdminController($events);
    }
    public function index(){
        return view('admin.home');
    }
    public function inicio_alta_usuarios(){
        $roles = Role::select(['id','guard_name'])
            ->orderBy('guard_name','ASC')
            ->get();
        $encabezado="Alta de Usuarios al Sistema";
        return view('admin.usuarios_alta')->with(compact('roles','encabezado'));
    }
    public function alta_usuario_rol(Request $request){
        $rol_id=$request->rol_id;
        $rol=Role::find($rol_id);
        if(Permiso::where('rol',$rol->name)->exists()){
            $permisos=Permiso::where('rol',$rol->name)->orderBy('descripcion')->get();
        }else{
            $permisos=[];
        }
        $encabezado="Alta de Usuarios al Sistema";
        return view('admin.usuarios_alta_rol')
            ->with(compact('rol','permisos','encabezado'));
    }
    public function crear_usuario(Request $request)
    {
        request()->validate([
            'nombre' => 'required',
            'usuario'=>'required|email|unique:users,email',
            'contra'=>'required|required_with:ccontra|same:ccontra',
            'ccontra'=>'required'
        ],[
            'nombre.required'=>'El nombre del usuario se requiere',
            'usuario.required'=>'El usuario es requerido',
            'usuario.email'=>'El usuario debe ser un email',
            'usuario.unique'=>'El usuario ya existe',
            'contra.required'=>'Debe escribir la nueva contraseña',
            'contra.required_with'=>'Debe confirmar la contraseña',
            'contra.same'=>'No concuerda con la verificación',
            'ccontra.required'=>'Debe confirmar la nueva contraseña'
        ]);
        $rol = Role::where('name', $request->rol)->first();
        $user = new User();
        $user->name = $request->nombre;
        $user->email =$request->usuario;
        $user->password =bcrypt($request->contra);
        $user->save();
        $user->roles()->attach($rol);
        if($request->bandera){
            $permisos=$request->input('permisos',[]);
            $user->syncPermissions($permisos);
        }
        $encabezado="Usuario Registrado correctamente";
        $mensaje="Se dió de alta al usuario, ya puede acceder al sistema";
        return view('admin.si')->with(compact('encabezado','mensaje'));
    }
}
