<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();

            //automatique
            $table->time('heure_attente')->nullable();
            $table->time('heure_arret')->nullable();
            $table->date('jour')->nullable();
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();

            //hotline
            $table->time('heure_demande')->nullable();
            $table->enum('appelle',['Cloturé','Non cloturé'])->nullable();
            $table->boolean('valide')->nullable();
            $table->enum('zone', ['Sertissage', 'Assemblage','Coupe','Préparation','Controle éléctrique'])->nullable();
            $table->bigInteger('tech_id')->unsigned();
            $table->enum('type_travaille',['C','PS','S','CS']);
            $table->bigInteger('code_equip')->unsigned()->nullable();
            $table->string('description_demande')->nullable();

            //technicien
            $table->string('travaille')->nullable();
            $table->string('piece_rechange')->nullable();

            $table->bigInteger('hotline_id')->unsigned();
            $table->foreign('hotline_id')->references('id')->on('users');
            $table->foreign('tech_id')->references('id')->on('users');
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
        Schema::dropIfExists('historiques');
    }
}
