<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos', function(Blueprint $table){
          $table->increments('id')->autoIncrement();
          $table->integer('id_user_from');
          $table->integer('id_user_to');
          $table->timestamps();
        });
        Schema::table('grupos', function(Blueprint $table){
          $table->foreign('id_user_from')->references('id')->on('usuarios');
          $table->foreign('id_user_to')->references('id')->on('usuarios');
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
