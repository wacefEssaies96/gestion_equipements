<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->date('jour')->nullable();
            $table->bigInteger('code_equip')->unsigned()->nullable();
            $table->enum('zone', ['Sertissage', 'Assemblage','Coupe','Préparation','Controle éléctrique'])->nullable();
            $table->string('commentaire')->nullable();
            $table->bigInteger('prod_id')->unsigned();
            $table->foreign('prod_id')->references('id')->on('users');
            $table->foreign('code_equip')->references('id')->on('equipements');
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
        Schema::dropIfExists('feedback');
    }
}
