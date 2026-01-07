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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id(); // incident_ID (PK)

            $table->string('incident_type');
            $table->text('description');
            $table->string('location');
            $table->dateTime('incident_date');
            $table->timestamp('incident_date');

            $table->string('evidence')->nullable();
            $table->string('status')->default('pending');

            $table->foreignId('reported_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps(); // createdAt + updatedAt
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
