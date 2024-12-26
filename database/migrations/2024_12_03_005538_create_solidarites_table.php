<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolidaritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solidarites', function (Blueprint $table) {
            $table->id(); // Identifiant unique pour chaque cotisation
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table users
            $table->integer('amount'); // Montant de la cotisation
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid'); // Statut de la cotisation
            $table->date('date'); // Date de la cotisation
            $table->timestamps();

            // Définir la clé étrangère avec une contrainte de suppression en cascade
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solidarites');
    }
}
