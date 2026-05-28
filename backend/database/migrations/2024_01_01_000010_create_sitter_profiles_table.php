<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sitter_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('city');
            $table->string('zip', 10)->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->enum('care_type', ['private', 'pension'])->default('private');
            $table->decimal('price_halfday', 8, 2)->nullable();
            $table->decimal('price_fullday', 8, 2)->nullable();
            $table->json('dog_sizes')->nullable(); // ['small','medium','large']
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sitter_profiles');
    }
};
