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
        Schema::table('places', function (Blueprint $table) {
            $table
                ->string('cid')
                ->nullable()
                ->after('google_place_id');
            $table
                ->longText('google_url')
                ->nullable()
                ->after('cid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('cid');
            $table->dropColumn('google_url');
        });
    }
};
