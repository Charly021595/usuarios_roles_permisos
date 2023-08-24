<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RPUsuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('nombre_usuario')->unique();
            $table->integer('no_empleado');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->integer('estatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('RPUsuarios');
    }
}
