<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function(Blueprint $table){
          $table->increments('id')->autoIncrement();
          $table->string('email', 175)->unique();
          $table->string('pass', 60);
          $table->string('nombre', 95);
          $table->string('apellido', 95);
          $table->string('api_token', 60)->unique();
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
        //
    }
}
