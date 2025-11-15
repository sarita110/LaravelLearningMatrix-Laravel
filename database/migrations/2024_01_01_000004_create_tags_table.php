<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color', 7)->default('#6C757D');
            $table->timestamps();
        });

        Schema::create('concept_tag', function (Blueprint $table) {
            $table->foreignId('concept_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['concept_id', 'tag_id']); // Composite PK prevents duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concept_tag');
        Schema::dropIfExists('tags');
    }
};
