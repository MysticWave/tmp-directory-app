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
        Schema::table('place_imports', function (Blueprint $table) {
            $table->string('task_type')->after('type');
            $table
                ->json('params')
                ->nullable()
                ->after('query');

            $table
                ->text('query')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('place_imports', function (Blueprint $table) {
            $table->dropColumn('task_type');
            $table->dropColumn('params');
        });
    }
};
