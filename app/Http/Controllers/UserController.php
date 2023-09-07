<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Validar_Usuario_AD;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function login_api(Request $request){
        try {
            $data = null;
            $validar_usuario_ad = new Validar_Usuario_AD();
            $jwtAuth = new JwtAuth();

            //Recibir POST
            $json = $request->input('json', null);
            $params = json_decode($json);

            $nombre_usuario = (!is_null($json) && isset($params->nombre_usuario)) ? $params->nombre_usuario : null;
            $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
            $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : true;

            //Cifrar la password
            $pwd = bcrypt($password);
            $usuario_validado_ad = $validar_usuario_ad->validar_usuario_ad($nombre_usuario, $password);
            // var_dump($usuario_validado_ad);
            // die();

            if (!is_null($nombre_usuario) && !is_null($password) && $usuario_validado_ad["validUser"]) {
                $signup = $jwtAuth->signup($nombre_usuario, $password);
                if ($signup["estatus"] === 'success') {
                    $user = $signup["user"];
                    $user->password = $pwd;
                    $user->update();
                }
                // var_dump($signup["user"]);
                // die();
            }

        }catch (\Throwable $th) {
            $data = array(
                'mensaje' => $th->getmessage(),
                'estatus' => 'error',
                'code' => 400
            );
        }
        return response()->json($signup, 200);
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
            // var_dump($datos_active);
            // die();

            if (!is_object($usuario_encontrado) && is_null($usuario_encontrado)) {
                $usuario = new User();
                $usuario->nombre = $request->editar_nombre_empleado;
                $usuario->nombre_usuario = $request->editar_nombre_equipo;
                $usuario->no_empleado = count($datos_active) != 0 ? $datos_active[0]->PERSONNELNUMBER : 0;
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
