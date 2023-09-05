<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Facades\DB;

class ServiciosASMXController extends Controller
{
    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper){
        $this->soapWrapper = $soapWrapper;
    }

    public function traer_users(Request $request){
        try {
            $data = null;
            $usuario = "Arzyz$2023AD";
            $password = "Arzyz$2020";

            $this->soapWrapper->add('ListadoEmpleado', function ($service) {
                $service
                    ->wsdl('http://192.168.1.202:84/wsArzyz/ObtenerInformacionAD.asmx?wsdl')
                    ->trace(true);
            });
            $results = $this->soapWrapper->call('ListadoEmpleado.ListadoEmpleado', [[
                'Usuario' => $usuario,
                'Contrasena' => $password
            ]]);

            $datos = json_decode($results->ListadoEmpleadoResult);

            if ($datos != '') {
                $data = array(
                    'datos' => $datos,
                    'estatus' => 'success',
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
}
