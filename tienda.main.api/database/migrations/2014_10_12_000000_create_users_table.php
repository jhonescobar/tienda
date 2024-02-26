<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('usu_id')        ->comment('Id Auto Incremental');
            $table->string('usu_username', 255) ->comment('Nombre de usuario');
            $table->string('usu_password', 200) ->comment('Password');
            $table->string('estado', 20)        ->comment('Estado');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE users comment 'Usuarios'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
