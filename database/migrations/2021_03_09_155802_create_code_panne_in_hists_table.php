<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodePanneInHistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_panne_in_hists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hist')->unsigned()->nullable();
            $table->bigInteger('code_panne')->unsigned()->nullable();
            $table->foreign('code_panne')->references('id')->on('code_pannes');
            $table->foreign('hist')->references('id')->on('historiques')->onDelete('cascade');
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
        Schema::dropIfExists('code_panne_in_hists');
    }
}
