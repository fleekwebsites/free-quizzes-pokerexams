<?php

namespace App\Console\Commands;

use Database\Seeders\DevDatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class SetupDevDatabase extends Command
{
    protected $signature = 'db:setup-dev {--fresh : Drop all tables before migrating}';

    protected $description = 'Create pokerexams_dev with Laravel defaults plus schools, courses, subjects, free_exams, and free_questions';

    public function handle(): int
    {
        $database = env('DB_DATABASE', 'pokerexams_dev');

        if ($this->option('fresh')) {
            $this->resetDatabase($database);
        } elseif (! $this->databaseExists($database)) {
            if (! $this->createDatabase($database)) {
                return self::FAILURE;
            }
        }

        $this->info("Using database: {$database}");

        Artisan::call('migrate', ['--force' => true]);
        $this->output->write(Artisan::output());

        Artisan::call('db:seed', [
            '--class' => DevDatabaseSeeder::class,
            '--force' => true,
        ]);
        $this->output->write(Artisan::output());

        $this->newLine();
        $this->info('Dev database ready.');
        $this->table(
            ['Table', 'Rows'],
            [
                ['schools', DB::table('schools')->count()],
                ['courses', DB::table('courses')->count()],
                ['subjects', DB::table('subjects')->count()],
                ['free_exams', DB::table('free_exams')->count()],
                ['free_questions', DB::table('free_questions')->count()],
            ]
        );

        return self::SUCCESS;
    }

    private function createDatabase(string $database): bool
    {
        try {
            $pdo = $this->pdo();
            $pdo->exec(
                "CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );
            $this->info("Database [{$database}] is ready.");

            return true;
        } catch (PDOException $exception) {
            $this->error('Could not create database: '.$exception->getMessage());

            return false;
        }
    }

    private function resetDatabase(string $database): void
    {
        $pdo = $this->pdo();
        $pdo->exec("DROP DATABASE IF EXISTS `{$database}`");
        $pdo->exec(
            "CREATE DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
        );
        $this->info("Database [{$database}] was reset.");
    }

    private function databaseExists(string $database): bool
    {
        $pdo = $this->pdo();
        $statement = $pdo->query('SHOW DATABASES LIKE '.$pdo->quote($database));

        return (bool) $statement->fetch();
    }

    private function pdo(): PDO
    {
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        return new PDO(
            "mysql:host={$host};port={$port}",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
