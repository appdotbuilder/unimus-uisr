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
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->text('description');
            $table->string('domain');
            $table->string('task');
            $table->string('license')->default('CC BY 4.0');
            $table->string('doi')->nullable();
            $table->enum('access_level', ['public', 'restricted', 'private'])->default('public');
            $table->enum('collaboration_type', ['national', 'international', 'local'])->default('local');
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'published', 'rejected'])->default('draft');
            $table->json('keywords')->nullable();
            $table->json('contributors')->nullable();
            $table->string('version')->default('1.0');
            $table->integer('download_count')->default(0);
            $table->integer('citation_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('domain');
            $table->index('collaboration_type');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};