<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tienda', function (Blueprint $table) {
            $table->increments('tie_id')       ->comment('Id Auto Incremental');
            $table->string('tie_nombre', 255)  ->comment('Nombre de la tienda');
            $table->integer('usuario_creacion')->nullable()->unsigned()->comment('Usuario que creo el registro');
            $table->string('estado', 20)       ->comment('Estado');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE tienda comment 'Tiendas'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tienda');
    }
}
