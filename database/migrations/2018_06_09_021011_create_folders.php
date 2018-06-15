<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('carpetas', function(Blueprint $table){
        $table->increments('id')->autoIncrement();
        $table->string('nombre', 250);
        $table->date('fecha_creacion');
        $table->string('id_grupo');
        $table->timestamps();
      });
      Schema::table('carpetas', function(Blueprint $table){
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
