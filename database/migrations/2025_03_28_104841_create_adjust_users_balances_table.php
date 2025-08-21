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
        Schema::create('adjust_users_balances', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('user_id')->nullable();
			$table->bigInteger('challenge_id')->nullable();
			$table->double('exact_amount_paid', 8, 2)->nullable();
			$table->double('amount_paid', 8, 2)->nullable();
			$table->string('percentage_value')->nullable();
			$table->tinyInteger('type')->nullable()->comment('0=remove, 1=add, 2=challenge amount');
			$table->tinyInteger('email_status')->default(1)->comment('0=email already send, 1=email not send');
			$table->tinyInteger('status')->nullable()->comment('0=No withdraw request, 1=Withdraw request sent, 2=Withdrawn request accept');
			$table->integer('trade_count')->nullable();
			$table->string('trade_pair')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjust_users_balances');
    }
};
