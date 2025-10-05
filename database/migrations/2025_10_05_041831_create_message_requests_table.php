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
        Schema::create('message_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('transition_number')->unique();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('provider_id');
            $table->string('type'); // WhatsApp, SMS, Email, etc.
            $table->json('message_config'); // Store message configuration as JSON
            $table->integer('message_count')->default(1);
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'read'])->default('pending');
            $table->json('request')->nullable(); // Store original request as JSON
            $table->json('response')->nullable(); // Store API response as JSON
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('entity_id')->references('id')->on('entity_masters')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('provider_masters')->onDelete('cascade');
            $table->index(['entity_id', 'provider_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_requests');
    }
};
