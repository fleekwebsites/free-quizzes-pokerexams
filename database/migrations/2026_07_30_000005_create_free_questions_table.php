<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }
};
