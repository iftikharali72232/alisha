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
        Schema::table('shops', function (Blueprint $table) {
            if (! Schema::hasColumn('shops', 'primary_color')) {
                $table->string('primary_color', 7)->nullable()->after('meta_description');
            }

            if (! Schema::hasColumn('shops', 'secondary_color')) {
                $table->string('secondary_color', 7)->nullable()->after('primary_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'secondary_color')) {
                $table->dropColumn('secondary_color');
            }

            if (Schema::hasColumn('shops', 'primary_color')) {
                $table->dropColumn('primary_color');
            }
        });
    }
};
