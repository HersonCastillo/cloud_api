<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos', function(Blueprint $table){
          $table->increments('id')->autoIncrement();
          $table->string('nombre', 250);
          $table->date('fecha_subida');
          $table->integer('id_user');
          $table->timestamps();
        });
        Schema::table('archivos', function(Blueprint $table){
          $table->foreign('id_user')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
