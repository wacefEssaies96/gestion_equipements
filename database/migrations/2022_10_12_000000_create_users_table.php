<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('tel');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['ADMIN', 'RESPONSABLE', 'PRODUCTION', 'TECHNICIEN', 'HOTLINE'])->nullable();
            $table->tinyInteger('etat')->default('1');
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
