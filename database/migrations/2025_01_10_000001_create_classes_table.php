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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('form_level'); // "Form 1", "Form 2", "Form 3", "Form 4", "Form 5", "Form 6", "Peralihan"
            $table->string('class_name'); // "AMANAH", "BESTARI", "ATAS 1", "CERIA 1", etc.
            $table->string('full_name')->virtualAs("CONCAT(form_level, ' ', class_name)"); // "Form 1 AMANAH"
            $table->boolean('is_active')->default(true); // Allow deactivating classes if needed
            $table->timestamps();
            
            // Ensure unique combination of form_level and class_name
            $table->unique(['form_level', 'class_name'], 'classes_form_level_class_name_unique');
            
            // Index for common queries
            $table->index(['form_level', 'is_active']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
}; 