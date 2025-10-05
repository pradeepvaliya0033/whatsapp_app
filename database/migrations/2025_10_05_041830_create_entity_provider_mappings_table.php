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
        Schema::create('entity_provider_mappings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('provider_id');
            $table->string('usage_type'); // WhatsApp, SMS, Email, etc.
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('entity_id')->references('id')->on('entity_masters')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('provider_masters')->onDelete('cascade');
            $table->unique(['entity_id', 'provider_id', 'usage_type']);
            $table->index(['entity_id', 'usage_type', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_provider_mappings');
    }
};
