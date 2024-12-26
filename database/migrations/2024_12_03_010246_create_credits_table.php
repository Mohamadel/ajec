<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('requested_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->string('status', 10)->default('Pending');
            $table->string('payment_status', 10)->default('Unpaid'); // Correctement défini ici
            $table->timestamp('date_borrowed')->nullable();
            $table->timestamp('date_due')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('rejected_date')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('credits'); // Suppression de la table en cas de rollback
    }
}
