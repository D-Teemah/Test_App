<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique()->index('Index_1')->nullable();
            $table->unsignedBigInteger('customer_id')->index();
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->decimal('commission', 18, 2)->default(0);
            $table->decimal('cost', 18, 2)->default(0);
            $table->decimal('channel_cost', 18, 2)->default(0);
            $table->decimal('channel_commission', 18, 2)->default(0);
            $table->string('status', 20)->index('Index_2');
            $table->string('type')->nullable();
            $table->longText('service_info')->nullable();
            $table->longText('receiver_info')->nullable();
            $table->string('vendor')->nullable();
            $table->string('session_id')->nullable();
            $table->boolean('is_reversed')->default(false);
            $table->string('provider_reference')->index()->nullable();
            $table->string('account_reference')->index()->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
