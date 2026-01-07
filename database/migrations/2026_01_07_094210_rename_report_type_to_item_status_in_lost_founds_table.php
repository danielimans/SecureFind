<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lost_founds', function (Blueprint $table) {
            $table->renameColumn('report_type', 'item_status');
        });
    }

    public function down(): void
    {
        Schema::table('lost_founds', function (Blueprint $table) {
            $table->renameColumn('item_status', 'report_type');
        });
    }
};