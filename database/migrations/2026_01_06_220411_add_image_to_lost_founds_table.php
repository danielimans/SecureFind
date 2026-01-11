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
        Schema::table('lost_founds', function (Blueprint $table) {
            if (!Schema::hasColumn('lost_founds', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lost_founds', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
