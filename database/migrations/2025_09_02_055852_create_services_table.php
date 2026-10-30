<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('yoga_type');
            $table->json('benefits');
            $table->decimal('price', 8, 2);
            $table->integer('capacity');
            $table->enum('currency', ['USD', 'NRS'])->default('NRS');
            $table->json('class_schedule');
            $table->string('session_time')->nullable();
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
