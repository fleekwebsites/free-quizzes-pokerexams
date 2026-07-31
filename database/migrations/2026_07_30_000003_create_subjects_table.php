<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subjectname');
            $table->unsignedInteger('course_id')->index();
            $table->timestamp('regdate')->useCurrent();

            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
