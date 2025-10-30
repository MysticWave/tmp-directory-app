<?php

use App\Enums\PlaceImportStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('place_imports', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('place_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->longText('query');
            $table
                ->string('status')
                ->default(PlaceImportStatus::PENDING->value);
            $table->string('squid_id');
            $table->string('run_id')->nullable();
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_imports');
    }
};
