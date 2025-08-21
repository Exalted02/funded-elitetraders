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
        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
			$table->integer('client_id')->nullable();
			$table->string('frontal')->nullable()->comment('File');
			$table->string('back')->nullable()->comment('file');
			$table->string('residence')->nullable()->comment('file');
			$table->tinyInteger('email_status')->default(1)->comment('0=email already send, 1=email not send');
			$table->tinyInteger('status')->default(1)->comment('0=reject, 1=pending, 2=accept');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
    }
};
