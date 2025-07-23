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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->date('start_at')->useCurrent()->nullable();
            $table->date('end_at')->useCurrent()->nullable();
            $table->boolean('is_active')->nullable();
            $table->boolean('auto_renew')->nullable();
            $table->string('payment_status')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('assigned_by');

            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
