<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subdivision_id')->index();
            $table->unsignedInteger('course_id')->index();
            $table->unsignedInteger('subject_id')->nullable()->index();
            $table->string('slug')->unique();
            $table->string('title');
            $table->unsignedSmallInteger('question_count')->default(0);
            $table->timestamps();

            $table->foreign('subdivision_id')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->foreign('subject_id')->references('id')->on('subjects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_exams');
    }
};
