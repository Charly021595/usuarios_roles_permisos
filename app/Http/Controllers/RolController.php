<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function vista_roles(){
        return view('roles.roles');
    }

    public function lista_roles(Request $request){
        try {
            $data = null;
            $rol_usuario = null;
            $roles_usuarios = array();
            $datos = array();

            $nombre_usuario = isset($request->nombre_usuario) && $request->nombre_usuario != '' ? $request->nombre_usuario : '';
            
            $roles = Role::all();

            if ($nombre_usuario != '') {
                $usuario_encontrado = User::select("*")->where("nombre_usuario", "=", $nombre_usuario)->first();
                
                if ($usuario_encontrado != '') {
                    $rol_usuarios_table = DB::table("user_has_roles")->where("user_has_roles.user_id", $usuario_encontrado->id)
                    ->pluck('user_has_roles.role_id','user_has_roles.role_id')
                    ->all();

                    foreach ($rol_usuarios_table as $rol_usuario_table) {
                        $rol_usuario = array("id_usuario_rol" => $rol_usuario_table);
                        array_push($roles_usuarios, $rol_usuario);
                    }

                    $datos = array("roles" => $roles, "rol_usuarios" => $roles_usuarios);   
                }
            }

            if ($roles->count() > 0) {
                if (count($datos) != 0) {
                    $data = array(
                        'datos' => $datos,
                        'estatus' => 'success',
                        'accion' => 'actualizado',
                        'code' => 200
                    );
                }else{
                    $data = array(
                        'datos' => $roles,
                        'estatus' => 'success',
                        'accion' => 'creado',
                        'code' => 200
                    );
                } 
            }else{
                $data = array(
                    'mensaje' => 'No hay registros',
                    'estatus' => 'error',
                    'code' => 200
                );  
            }

        } catch (\Throwable $th) {
            $data = array(
                'message' => $th->getmessage(),
                'estatus' => 'error',
                'code' => 400
            );
        }

        return $data;
    }

    public function guardar_roles(Request $request){
        try {
            DB::beginTransaction();
            $data = null;

            $this->validate($request, [
                'guardar_nombre_rol' => 'required',
                'guardar_descripcion_rol' => 'required',
                'guardar_estatus_rol' => 'required',
                'permisos' => 'required'
            ]);
            
            $array_nuevo = array('_token' => $request->_token, 'name' => $request->guardar_nombre_rol, 
            'descripcion' => $request->guardar_descripcion_rol, 'estatus' => $request->guardar_estatus_rol);
    
            $rol = Role::create($array_nuevo);
            $rol->permissions()->attach($request->permisos);
            if ($rol) {
                DB::commit();
                $data = array(
                    'mensaje' => 'El rol se creo con éxito',
                    'estatus' => 'success',
                    'code' => 200
                );   
            }else{
                $data = array(
                    'mensaje' => 'El rol no se creó con éxito',
                    'estatus' => 'error',
                    'code' => 200
                );  
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'estatus' => 'error',
                'code' => 400
            );
        }

        return $data;
    }

    public function buscar_roles(Request $request){
        try {
            $data = null;

            $nombre_rol = mb_strtoupper($request->nombre_rol);
            $descripcion_rol = mb_strtoupper($request->descripcion_rol);
            $estatus_rol = $request->estatus_rol;
            
            $roles = Role::select('*');

            if ($nombre_rol != null && $nombre_rol != '') {
                $roles->where('name', 'like', '%'.$nombre_rol.'%');
            }
    
            if ($descripcion_rol != null && $descripcion_rol != '') {
                $roles->where('descripcion', 'like', '%'.$descripcion_rol.'%');
            }
    
            if ($estatus_rol != null && $estatus_rol != '') {
                $roles->where('estatus', '=', $estatus_rol);
            }

            $resultados = $roles->orderBy('id', 'desc')->get();
    
            if ($resultados->count() > 0) {
                $data = array(
                    'datos' => $resultados,
                    'estatus' => 'success',
                    'code' => 200
                );   
            }else{
                $data = array(
                    'mensaje' => 'No hay registros',
                    'estatus' => 'error',
                    'code' => 200
                );  
            }

        } catch (\Throwable $th) {
            $data = array(
                'message' => $th->getmessage(),
                'estatus' => 'error',
                'code' => 400
            );
        }

        return $data;
    }

    public function traer_permisos_roles(Request $request){
        try {
            DB::beginTransaction();
            $data = null;

            $this->validate($request, [
                'id_rol' => 'required'
            ]);

            $id = $request->id_rol;
            $rol_permisos = array();

            $rol = Role::find($id);
            $permisos = Permission::all();
            $rol_permisos_table = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();

            foreach ($rol_permisos_table as $rol_permiso_table) {
                $rol_permiso = array("id_permiso_rol" => $rol_permiso_table);
                array_push($rol_permisos, $rol_permiso);
            }

            $datos = array("permisos" => $permisos, "rol_permisos" => $rol_permisos);

            if ($rol->count() != 0 && $permisos->count() != 0){
                DB::commit();
                $data = array(
                    'datos' => $datos,
                    'estatus' => 'success',
                    'code' => 200
                );   
            }else{
                $data = array(
                    'mensaje' => 'Este rol no tiene permisos asignados',
                    'estatus' => 'error',
                    'code' => 200
                );  
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'estatus' => 'error',
                'code' => 400
            );
        }

        return $data;
    }

    public function actualizar_roles(Request $request){
        try {
            DB::beginTransaction();
            $data = null;

            $this->validate($request, [
                'id_rol' => 'required',
                'editar_nombre_rol' => 'required',
                'editar_descripcion_rol' => 'required',
                'editar_estatus_rol' => 'required'
            ]);

            $rol = Role::find($request->id_rol);
            $rol->name = $request->editar_nombre_rol;
            $rol->descripcion = $request->editar_descripcion_rol;
            $rol->estatus = $request->editar_estatus_rol;
            $rol->permissions()->sync($request->permisos_editar);

            if ($rol->update()) {
                DB::commit();
                $data = array(
                    'mensaje' => 'El rol se Actualizó con éxito',
                    'estatus' => 'success',
                    'code' => 200
                );   
            }else{
                $data = array(
                    'mensaje' => 'El rol no se Actualizó con éxito',
                    'estatus' => 'error',
                    'code' => 200
                );
            } 
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'estatus' => 'error',
                'code' => 400
            ); 
        }

        return $data;
    }

    public function eliminar_roles(Role $rol){
        try {
            $rol->delete();
            return redirect()->route('roles')->with('info', 'El rol se eliminó con exito');
        } catch (\Throwable $th) {
            return redirect()->route('roles')->with('danger', 'El rol no se eliminó con exito');
        }
    }
}
