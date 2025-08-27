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
        Schema::table('challenges', function (Blueprint $table) {            
			$table->date('trade_date')->nullable()->after('status');
			$table->integer('trade_count')->nullable()->after('trade_date');
			$table->string('trade_pair')->nullable()->after('trade_count');
			$table->double('trade_result', 8, 2)->nullable()->after('trade_pair');
			$table->date('funded_date')->nullable()->after('trade_result');
			$table->tinyInteger('funded_email_status')->default(0)->comment('0=email already send, 1=email not send')->after('funded_date');
			$table->integer('account_size_rand_number')->nullable()->after('funded_email_status');
			$table->tinyInteger('challenge_type')->default(1)->comment('0=Old, 1=New')->after('account_size_rand_number');
			$table->bigInteger('parent_paid_challenge_id')->nullable()->comment('This is same(challenges) table id. If this field is null then this is paid challenge otherwise free challenge')->after('challenge_type');
			$table->double('phase_one_amount', 8, 2)->nullable()->after('parent_paid_challenge_id');
			$table->double('phase_two_amount', 8, 2)->nullable()->after('phase_one_amount');
			$table->tinyInteger('new_challenge_email_status')->default(0)->comment('0=email already send, 1=email not send')->after('phase_two_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            //
        });
    }
};
