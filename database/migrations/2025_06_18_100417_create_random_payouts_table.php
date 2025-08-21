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
        Schema::create('random_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('login_id')->index()->nullable();
            $table->string('status', 36)->index()->nullable();
            $table->string('account_size', 36)->index()->nullable();
            $table->double('account_amount', 8, 2)->index()->nullable();
            $table->double('profit', 8, 2)->index()->nullable();
            $table->string('country_name')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('random_payouts');
    }
};
