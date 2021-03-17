<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodePannesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_pannes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('designation')->nullable();
            $table->enum('zone', ['Sertissage', 'Assemblage','Coupe','Préparation','Controle éléctrique'])->nullable();
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
        Schema::dropIfExists('code_pannes');
    }
}
