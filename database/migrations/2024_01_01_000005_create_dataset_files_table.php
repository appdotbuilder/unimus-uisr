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
        Schema::create('dataset_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('path');
            $table->bigInteger('size');
            $table->string('mime_type');
            $table->string('extension');
            $table->json('metadata')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index('dataset_id');
            $table->index('extension');
            $table->index(['dataset_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset_files');
    }
};