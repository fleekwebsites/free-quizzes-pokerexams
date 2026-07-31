<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE free_questions MODIFY choiceA TEXT NULL');
        DB::statement('ALTER TABLE free_questions MODIFY choiceB TEXT NULL');
        DB::statement('ALTER TABLE free_questions MODIFY choiceC TEXT NULL');
        DB::statement('ALTER TABLE free_questions MODIFY choiceD TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE free_questions MODIFY choiceA TEXT NOT NULL');
        DB::statement('ALTER TABLE free_questions MODIFY choiceB TEXT NOT NULL');
        DB::statement('ALTER TABLE free_questions MODIFY choiceC TEXT NOT NULL');
        DB::statement('ALTER TABLE free_questions MODIFY choiceD TEXT NOT NULL');
    }
};
