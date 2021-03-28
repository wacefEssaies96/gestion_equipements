<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistAssemblagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_assemblages', function (Blueprint $table) {
            $table->id();
            $table->string('num_planche')->nullable();
            $table->bigInteger('historique_id')->unsigned();
            $table->foreign('historique_id')->references('id')->on('historiques')->onDelete('cascade');
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
        Schema::dropIfExists('hist_assemblages');
    }
}
