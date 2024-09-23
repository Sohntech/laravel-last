<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('adresse');
            $table->string('telephone');
            $table->string('fonction');
            $table->string('email')->unique();
            $table->text('photo')->nullable();
            $table->enum('statut', ['Bloqué', 'Actif']);
            $table->string('password'); // Colonne du mot de passe
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users');
    }
};
