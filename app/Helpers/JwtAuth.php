<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = 'arzyz-beyond-aluminum-mastodonte-secreta-758458934753987493';
    }

    public function signup($nombre_usuario, $password, $getToken = null){
        try {
            DB::beginTransaction();
            $data = null;

            $user = User::where(
                array(
                    "nombre_usuario" => $nombre_usuario
                ))->first();
    
            $signup = false;

            if (is_object($user) && !is_null($user)) {
                $signup = true;
            }

            if ($signup) {
                DB::commit();
                //Generar el token y devolverlo
                $token = array( 
                    'sub' => $user->id,
                    'email' => $user->email,
                    'nombre' => $user->nombre,
                    'nombre_equipo' => $user->nombre_usuario,
                    'no_empleado' => $user->no_empleado,
                    'iat' => time(),
                    'exp' => time() + (7 * 24 * 60 * 60)
                );

                $jwt = JWT::encode($token, $this->key, 'HS256');
                $decoded = JWT::decode($jwt, $this->key, array('HS256'));

                if (!is_null($getToken)) {
                    $data = array(
                        'token' => $jwt,
                        'decode' => $decoded,
                        'user' => $user,
                        'estatus' => 'success',
                        'code' => 200
                    );
                }else{
                    $data = array(
                        'token' => $jwt,
                        'decode' => $decoded,
                        'user' => $user,
                        'estatus' => 'success',
                        'code' => 200
                    );
                }

            }else{
                // Devolver un error
                $data = array(
                    'message' => 'No se han asignado permisos para esta aplicación',
                    'estatus' => 'error',
                    'user' => $user,
                    'code' => 400
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

    public function checkToken($jwt, $getIdentity = false){
        try {
            $data = null;
            $auth = false;

            $decoded = JWT::decode($jwt, $this->key, array('HS256'));

            if (is_object($decoded) && isset($decoded->sub)) {
                $auth = true;
            }else{
                $auth = false;
            }

            if ($getIdentity) {
                $data = array(
                    'decoded' => $decoded,
                    'authentication' => $auth,
                    'estatus' => 'success',
                    'code' => 200
                );
            }
            
        }catch (\UnexpectedValueException $e) {
            $data = array(
                'authentication' => $auth,
                'estatus' => 'error',
                'code' => 400
            );
        }catch (\DomainException $e) {
            $data = array(
                'authentication' => $auth,
                'estatus' => 'error',
                'code' => 400
            );
        }
        return $data;
    }
}

?>