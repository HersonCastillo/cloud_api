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
          $table->integer('id_grupo');
          $table->timestamps();
        });
        Schema::table('archivos', function(Blueprint $table){
          $table->foreign('id_grupo')->references('id')->on('grupos');
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
