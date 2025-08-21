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
        Schema::create('cron_kyc_documents', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('kyc_documents_id');
            $table->tinyInteger('status_type')->comments('0=reject, 2=accept');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_kyc_documents');
    }
};
