<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Role_has_permissions;

class PermisoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function vista_permisos(){
        return view('permisos.permisos');
    }

    public function lista_permisos(Request $request){
        try {
            $data = null;
            
            $permisos = Permission::all();

            if ($permisos->count() > 0) {
                $data = array(
                    'datos' => $permisos,
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

    public function guardar_permisos(Request $request){
        try {
            $data = null;

            $this->validate($request, [
                'guardar_nombre_permiso' => 'required',
                'guardar_descripcion' => 'required',
                'guardar_estatus_permiso' => 'required'
            ]);
            
            $permiso = Permission::create(['name' => $request->guardar_nombre_permiso, 'descripcion' => $request->guardar_descripcion, 'estatus' => $request->guardar_estatus_permiso]);

            if ($permiso) {
                $data = array(
                    'mensaje' => 'El permiso se creó con éxito',
                    'estatus' => 'success',
                    'code' => 200
                );   
            }else{
                $data = array(
                    'mensaje' => 'El permiso no se creó con éxito',
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

    public function buscar_permisos(Request $request){
        try {
            $data = null;

            $nombre_permiso = mb_strtoupper($request->nombre_permiso);
            $descripcion_permiso = mb_strtoupper($request->descripcion);
            $estatus_permiso = $request->estatus_permiso;
            
            $permisos = Permission::select('*');

            if ($nombre_permiso != null && $nombre_permiso != '') {
                $permisos->where('name', 'like', '%'.$nombre_permiso.'%');
            }
    
            if ($descripcion_permiso != null && $descripcion_permiso != '') {
                $permisos->where('descripcion', 'like', '%'.$descripcion_permiso.'%');
            }
    
            if ($estatus_permiso != null && $estatus_permiso != '') {
                $permisos->where('estatus', '=', $estatus_permiso);
            }

            $resultados = $permisos->orderBy('id', 'desc')->get();
    
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

    public function actualizar_permisos(Request $request){
        try {
            $data = null;

            $this->validate($request, [
                'editar_nombre_permiso' => 'required',
                'editar_descripcion' => 'required',
                'editar_estatus_permiso' => 'required'
            ]);

            $id_permiso = $request->permiso;

            $permiso = Permission::findOrFail($id_permiso);
            $permiso->name = $request->editar_nombre_permiso;
            $permiso->descripcion = $request->editar_descripcion;
            $permiso->estatus = $request->editar_estatus_permiso;

            if ($permiso->update()) {
                $data = array(
                    'mensaje' => 'El permiso se actualizo con éxito',
                    'estatus' => 'success',
                    'code' => 200
                );   
            }else{
                $data = array(
                    'mensaje' => 'El permiso no se actualizo con éxito',
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

    public function eliminar_permisos(Permission $permiso){
        try {
            $permiso->delete();
            return redirect()->route('permisos')->with('info', 'El permiso se eliminó con exito');
        } catch (\Throwable $th) {
            return redirect()->route('permisos')->with('danger', 'El permiso no se eliminó con exito');
        }
    }
}
