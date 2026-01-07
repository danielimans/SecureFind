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
           $table->enum('report_type', ['lost', 'found'])->after('item_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_founds', function (Blueprint $table) {
            $table->dropColumn('report_type');
        });
    }
};
