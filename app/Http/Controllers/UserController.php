<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function guardar_usuarios(Request $request){
        try {
            DB::beginTransaction();
            $data = null;

            $this->validate($request, [
                'editar_nombre_empleado' => 'required',
                'editar_nombre_equipo' => 'required',
                'editar_estatus_usuario' => 'required'
            ]);

            // if (strlen($request->password_usuario) < 8) {
            //     throw new \Exception( 'La Contraseña debe ser de 8 caracteres como minimo.');
            // }
            $password = "Arzyz$2023";

            $usuario_encontrado = User::select("*")->where("nombre_usuario", "=", $request->editar_nombre_equipo)->first();
            $valores = array($request->editar_nombre_equipo); 
            $datos_active = DB::connection("sqlsrv2")->select("ARZUsuarioSC ?", $valores);

            if ($usuario_encontrado->count() == 0) {
                $usuario = new User();
                $usuario->nombre = $request->editar_nombre_empleado;
                $usuario->nombre_usuario = $request->editar_nombre_equipo;
                $usuario->no_empleado = $datos_active[0]->PERSONNELNUMBER;
                $usuario->email = $request->editar_nombre_equipo.'@arzyz.com';
                $usuario->estatus = $request->editar_estatus_usuario;
                $usuario->password = bcrypt($password);

                if ($usuario->save()) {
                    $usuario->roles()->sync($request->roles);
                    DB::commit();
                    $data = array(
                        'mensaje' => 'El usuario se edito con éxito',
                        'estatus' => 'success',
                        'accion' => 'creado',
                        'code' => 200
                    );
                }else{
                    $data = array(
                        'mensaje' => 'El usuario no pudo ser editado',
                        'estatus' => 'error',
                        'code' => 200
                    );
                }
            }else{
                $usuario_encontrado->nombre = $request->editar_nombre_empleado;
                $usuario_encontrado->nombre_usuario = $request->editar_nombre_equipo;
                $usuario_encontrado->no_empleado = $datos_active[0]->PERSONNELNUMBER;
                $usuario_encontrado->email = $request->editar_nombre_equipo.'@arzyz.com';
                $usuario_encontrado->estatus = $request->editar_estatus_usuario;
                $usuario_encontrado->password = bcrypt($password);

                if ($usuario_encontrado->update()) {
                    $usuario_encontrado->roles()->sync($request->roles);
                    DB::commit();
                    $data = array(
                        'mensaje' => 'El usuario se edito con éxito',
                        'estatus' => 'success',
                        'accion' => 'actualizado',
                        'code' => 200
                    );
                }else{
                    $data = array(
                        'mensaje' => 'El usuario no pudo ser editado',
                        'estatus' => 'error',
                        'code' => 200
                    );
                }
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

    public function eliminar_usuario(User $usuario){
        $usuario->delete();
        return redirect()->route('admin.usuarios')->with('info', 'El usuario se eliminó con exitó');
    }
}
