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
            $table->string('code')->unique();
            $table->string('designation')->nullable();
            $table->string('code_section')->nullable();
            $table->string('emplacement')->nullable();
            $table->string('code_categorie')->nullable();
            $table->string('desi_cat')->nullable();
            $table->date('date_acq')->default(now('Africa/Tunis'));
            $table->string('n_serie')->nullable();
            $table->string('constructeur')->nullable();
            $table->string('image')->nullable();
            $table->enum('etat', ['Production', 'Zsav','obsolete'])->nullable();
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
