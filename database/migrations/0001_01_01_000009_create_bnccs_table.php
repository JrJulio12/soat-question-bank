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
        Schema::create('bnccs', function (Blueprint $table) {
            $table->id();
            $table->string('stage', 2); // EF or EM
            $table->string('code')->unique();
            $table->text('description');
            $table->foreignId('serie_id')->constrained()->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->foreignId('knowledge_id')->nullable()->constrained('knowledges')->onDelete('cascade');
            $table->timestamps();

            $table->index('serie_id');
            $table->index('discipline_id');
            $table->index('knowledge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bnccs');
    }
};
