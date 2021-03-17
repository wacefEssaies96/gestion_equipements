<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->string('designation');
            $table->string('n_serie');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('equipements');
    }
}
