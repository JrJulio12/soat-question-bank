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
        Schema::create('bncc_knowledge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bncc_id')->constrained()->onDelete('cascade');
            $table->foreignId('knowledge_id')->constrained('knowledges')->onDelete('cascade');

            $table->unique(['bncc_id', 'knowledge_id']);
            $table->index('bncc_id');
            $table->index('knowledge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bncc_knowledge');
    }
};


