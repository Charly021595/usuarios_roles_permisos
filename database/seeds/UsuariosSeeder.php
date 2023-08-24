<?php

use App\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $password = "123";

        $user = new User([
            'nombre' => 'Control Maestro',
            'nombre_usuario' => 'mastodonte',
            'no_empleado' => 1000000000,
            'email' => 'admin@arzyz.com',
            'password' => bcrypt($password),
            'remember_token' => '',
            'estatus' => 1
        ]);
        $user->saveOrFail();
    }
}
