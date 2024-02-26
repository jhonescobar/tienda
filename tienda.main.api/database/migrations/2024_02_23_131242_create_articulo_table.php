<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo', function (Blueprint $table) {
            $table->increments('art_id')       ->comment('Id Auto Incremental');
            $table->unsignedInteger('tie_id')  ->comment('Id Tienda');
            $table->string('art_nombre', 255)  ->comment('Nombre del articulo');
            $table->double('art_precio')       ->comment('Precio del articulo');
            $table->integer('usuario_creacion')->nullable()->unsigned()->comment('Usuario que creo el registro');
            $table->string('estado', 20)       ->comment('Estado');
            $table->timestamps();
            $table->foreign('tie_id', 'fk1_tienda_tie_id')->references('tie_id')->on('tienda')->comment('Indice Tienda');
        });
        DB::statement("ALTER TABLE articulo comment 'Articulos'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulo');
    }
}
