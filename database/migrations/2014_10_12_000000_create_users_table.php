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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_type')->default(1)->comment('0=Admin, 1=Client');
            $table->string('name');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
            $table->string('email')->unique();
			$table->string('phone_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
			$table->string('profile_image')->nullable();
            $table->rememberToken();
            $table->integer('otp')->nullable();
            $table->integer('country')->nullable();
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
            $table->longText('address')->nullable();
			$table->string('status')->nullable()->comment('0=inactive/suspend, 1=active, 2=delete');
			$table->string('trading_account_id')->nullable()->index();
			$table->string('trading_account_pw')->nullable()->index();
			$table->tinyInteger('eligible_withdraw')->default(0)->comment('0=not withdrawable, 1=withdrawable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
