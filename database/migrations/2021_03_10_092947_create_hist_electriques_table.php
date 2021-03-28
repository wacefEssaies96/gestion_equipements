<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistElectriquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_electriques', function (Blueprint $table) {
            $table->id();
            $table->string('nom_support')->nullable();
            $table->bigInteger('hist_id')->unsigned();
            $table->foreign('hist_id')->references('id')->on('historiques')->onDelete('cascade');
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
        Schema::dropIfExists('hist_electriques');
    }
}