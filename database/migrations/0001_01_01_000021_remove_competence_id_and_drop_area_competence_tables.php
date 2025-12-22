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
        // Remove competence_id from bnccs table
        Schema::table('bnccs', function (Blueprint $table) {
            $table->dropForeign(['competence_id']);
            $table->dropIndex(['competence_id']);
            $table->dropColumn('competence_id');
        });

        // Drop competences table
        Schema::dropIfExists('competences');

        // Drop areas table
        Schema::dropIfExists('areas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate areas table
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Recreate competences table
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->text('description');
            $table->timestamps();

            $table->index('area_id');
        });

        // Re-add competence_id to bnccs table
        Schema::table('bnccs', function (Blueprint $table) {
            $table->foreignId('competence_id')->nullable()->after('discipline_id')->constrained()->onDelete('cascade');
            $table->index('competence_id');
        });
    }
};

