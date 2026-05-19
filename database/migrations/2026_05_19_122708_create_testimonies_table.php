<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('testimonies')) {
            Schema::create('testimonies', function (Blueprint $table) {
                $table->id();
                $table->string('author_name');
                $table->text('content');
                $table->string('image')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonies');
    }
};
