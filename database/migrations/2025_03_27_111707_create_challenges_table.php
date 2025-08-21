<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
			$table->string('client_id')->nullable()->index();
			$table->string('client_pw')->nullable()->index();
			$table->bigInteger('user_id');
			$table->string('email')->nullable();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('phone')->nullable();
			$table->bigInteger('challenge_id')->nullable();
			$table->double('amount_paid', 8, 2)->nullable();
			$table->string('proof_document')->nullable();
			$table->longText('comment')->nullable();
			$table->tinyInteger('status')->nullable()->comment('0=on-challenge, 1=funded, 2=failed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
