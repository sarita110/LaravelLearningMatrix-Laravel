<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();         // e.g. "service-providers"
            $table->text('description');              // Plain-text explanation
            $table->longText('explanation')->nullable(); // Rich markdown explanation
            $table->longText('code_example')->nullable(); // The featured code snippet
            $table->string('code_language')->default('php'); // php, blade, bash, json
            $table->tinyInteger('phase')->unsigned(); // 1–7
            $table->boolean('is_published')->default(false);
            $table->integer('view_count')->default(0);
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};
