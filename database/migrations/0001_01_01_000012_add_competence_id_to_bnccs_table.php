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
        Schema::table('bnccs', function (Blueprint $table) {
            $table->foreignId('competence_id')->nullable()->after('knowledge_id')->constrained()->onDelete('cascade');
            $table->index('competence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bnccs', function (Blueprint $table) {
            $table->dropForeign(['competence_id']);
            $table->dropIndex(['competence_id']);
            $table->dropColumn('competence_id');
        });
    }
};

