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
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_id')->nullable()->unique();
            $table->string('facebook_name')->nullable();
            $table->string('facebook_email')->nullable();
            $table->text('facebook_picture')->nullable();
            $table->text('facebook_access_token')->nullable();
            $table->timestamp('facebook_token_expires_at')->nullable();
            $table->text('facebook_pages')->nullable(); // JSON of user's pages
            $table->string('facebook_selected_page_id')->nullable();
            $table->string('facebook_selected_page_name')->nullable();
            $table->text('facebook_selected_page_token')->nullable();
            $table->timestamp('facebook_connected_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_id',
                'facebook_name',
                'facebook_email',
                'facebook_picture',
                'facebook_access_token',
                'facebook_token_expires_at',
                'facebook_pages',
                'facebook_selected_page_id',
                'facebook_selected_page_name',
                'facebook_selected_page_token',
                'facebook_connected_at'
            ]);
        });
    }
};
