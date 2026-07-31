<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coursename', 255);
            $table->string('slug')->index();
            $table->unsignedInteger('school_id')->index();
            $table->boolean('is_visible')->default(true);
            $table->timestamp('regdate')->useCurrent();

            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
