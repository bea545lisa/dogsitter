<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sitter_id')->constrained('users')->cascadeOnDelete();
            $table->string('dog_name');
            $table->enum('dog_size', ['small', 'medium', 'large']);
            $table->date('from_date');
            $table->date('to_date');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'cancelled'])
                  ->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
