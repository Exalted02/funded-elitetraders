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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
			$table->text('certificate_id')->nullable();
			$table->date('certificate_date')->nullable();
			$table->string('certificate_name')->nullable();
			$table->double('certificate_amount', 8, 2)->nullable();
			$table->tinyInteger('status')->nullable()->default(1)->comment('1=active, 2=delete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
