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
            $table->dropForeign(['serie_id']);
            $table->dropIndex(['serie_id']);
            $table->dropColumn('serie_id');

            $table->dropForeign(['knowledge_id']);
            $table->dropIndex(['knowledge_id']);
            $table->dropColumn('knowledge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bnccs', function (Blueprint $table) {
            $table->foreignId('serie_id')->constrained()->onDelete('cascade');
            $table->index('serie_id');

            $table->foreignId('knowledge_id')->nullable()->constrained('knowledges')->onDelete('cascade');
            $table->index('knowledge_id');
        });
    }
};


