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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('place_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('source');
            $table->string('external_review_id')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_url')->nullable();
            $table->string('author_image_url')->nullable();
            $table->string('author_external_id')->nullable();
            $table->integer('rating')->default(0);
            $table->longText('text')->nullable();
            $table->longText('original_text')->nullable();
            $table->boolean('is_translated')->default(false);
            $table->string('language')->nullable();
            $table->integer('likes_count')->default(0);
            $table->integer('reviews_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();

            $table->unique(['source', 'external_review_id']);
            $table->index('rating');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
