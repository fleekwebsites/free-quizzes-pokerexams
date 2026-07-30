<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->text('schoolname');
            $table->string('slug')->nullable()->index();
            $table->timestamp('regdate')->useCurrent();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coursename', 255);
            $table->string('slug')->index();
            $table->unsignedInteger('school_id')->index();
            $table->boolean('is_visible')->default(true);
            $table->timestamp('regdate')->useCurrent();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subjectname');
            $table->unsignedInteger('course_id')->index();
            $table->timestamp('regdate')->useCurrent();
        });

        Schema::create('free_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subdivision_id')->index();
            $table->unsignedInteger('course_id')->index();
            $table->unsignedInteger('subject_id')->nullable()->index();
            $table->string('slug')->unique();
            $table->string('title');
            $table->unsignedSmallInteger('question_count')->default(0);
            $table->timestamps();
        });

        Schema::create('free_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('free_exam_id')->constrained('free_exams')->cascadeOnDelete();
            $table->text('extract')->nullable();
            $table->text('question');
            $table->text('choiceA');
            $table->text('choiceB');
            $table->text('choiceC');
            $table->text('choiceD');
            $table->text('choiceE')->nullable();
            $table->text('choiceF')->nullable();
            $table->text('choiceG')->nullable();
            $table->string('correctAnswer');
            $table->text('rationale');
            $table->string('image', 500)->nullable();
            $table->string('qtype')->default('Regular');
            $table->text('heading')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_questions');
        Schema::dropIfExists('free_exams');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('schools');
    }
};
