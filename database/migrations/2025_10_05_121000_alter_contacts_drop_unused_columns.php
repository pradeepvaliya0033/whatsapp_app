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
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'country_code')) {
                $table->dropColumn('country_code');
            }
            if (Schema::hasColumn('contacts', 'company')) {
                $table->dropColumn('company');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'country_code')) {
                $table->string('country_code', 8)->nullable();
            }
            if (!Schema::hasColumn('contacts', 'company')) {
                $table->string('company')->nullable();
            }
        });
    }
};


