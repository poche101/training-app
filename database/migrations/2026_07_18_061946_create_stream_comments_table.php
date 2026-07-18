<?php

// database/migrations/xxxx_xx_xx_create_stream_comments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stream_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()
                  ->constrained('stream_comments')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('body');
            $table->boolean('is_admin')->default(false);
            $table->foreignId('admin_id')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['livestream_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stream_comments');
    }
};

