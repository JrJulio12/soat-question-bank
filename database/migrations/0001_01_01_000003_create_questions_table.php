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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('stage', 2); // EF or EM
            $table->string('type', 20); // multiple_choice, multi_select, true_false, open
            $table->longText('stem'); // TEXT/LONGTEXT, could be LaTeX
            $table->text('answer_text')->nullable(); // Only when type is open
            $table->string('status', 20); // draft or published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

