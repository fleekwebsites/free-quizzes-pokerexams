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
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
