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
        Schema::create('client_payout_requests', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('user_id')->nullable();
			$table->double('requested_amount', 8, 2)->nullable();
			$table->longText('withdrawable_adjust_id')->nullable()->comment('adjust_users_balances table ids');
			$table->tinyInteger('status')->nullable()->comment('0=pending, 1=Accept, 2=Reject');
			$table->longText('reason')->nullable();
			$table->string('usdc_address')->nullable();
			$table->string('usdc_edit_address')->nullable();
			$table->string('crypto_options')->nullable();
			$table->string('crypto_platform')->nullable();
			$table->string('crypto_phone')->nullable();
			$table->tinyInteger('crypto_experience')->nullable()->comment('1=Yes, 0=No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_payout_requests');
    }
};
