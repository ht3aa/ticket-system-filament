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
        Schema::create('project_labels', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->unique('title', 'project_id');
            $table->text('description')->nullable();
            $table->string('color', 255);
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_labels');
    }
};
