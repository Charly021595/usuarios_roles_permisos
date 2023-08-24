<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-permission.table_names');
        $foreignKeys = config('laravel-permission.foreign_keys');

        Schema::create($tableNames['RPRoles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create($tableNames['RPPermissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create($tableNames['RPUser_has_permissions'], function (Blueprint $table) use ($tableNames, $foreignKeys) {
            $table->integer($foreignKeys['RPUsuarios'])->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign($foreignKeys['RPUsuarios'])
                ->references('id')
                ->on($tableNames['RPUsuarios'])
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['RPPermissions'])
                ->onDelete('cascade');

            $table->primary([$foreignKeys['RPUsuarios'], 'permission_id']);
        });

        Schema::create($tableNames['RPUser_has_roles'], function (Blueprint $table) use ($tableNames, $foreignKeys) {
            $table->integer('role_id')->unsigned();
            $table->integer($foreignKeys['RPUsuarios'])->unsigned();

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['RPRoles'])
                ->onDelete('cascade');

            $table->foreign($foreignKeys['RPUsuarios'])
                ->references('id')
                ->on($tableNames['RPUsuarios'])
                ->onDelete('cascade');

            $table->primary(['role_id', $foreignKeys['RPUsuarios']]);
        });

        Schema::create($tableNames['RPRole_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['RPPermissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['RPRoles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('laravel-permission.table_names');

        Schema::drop($tableNames['RPRole_has_permissions']);
        Schema::drop($tableNames['RPUser_has_roles']);
        Schema::drop($tableNames['RPUser_has_permissions']);
        Schema::drop($tableNames['RPRoles']);
        Schema::drop($tableNames['RPPermissions']);
    }
}
