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
       Schema::create('lost_founds', function (Blueprint $table) {
            $table->id();

            $table->string('item_name');
            $table->string('item_category');
            $table->enum('item_status', ['lost', 'found']);
            $table->string('location');
            $table->text('description');

            $table->string('image')->nullable();
            $table->string('claim_status')->default('open');

            $table->foreignId('reported_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_founds');
    }
};
