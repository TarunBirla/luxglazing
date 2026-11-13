<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;
use Throwable;

class DatabaseSetupController extends Controller
{
    /**
     * Run full database creation, migration and seeding via single route hit.
     * Route: /setup-database or /init-database or /setup-database?key=luxglazing
     */
    public function setup(Request $request)
    {
        $logs = [];
        $status = 'success';
        $message = 'Database and tables created successfully!';

        try {
            // 1. Detect DB connection configuration
            $connection = config('database.default', 'mysql');
            $dbConfig = config("database.connections.{$connection}", []);

            $logs[] = "Target Database Engine: [{$connection}]";

            // If MySQL, ensure the database exists or create it
            if ($connection === 'mysql') {
                $host = $dbConfig['host'] ?? '127.0.0.1';
                $port = $dbConfig['port'] ?? '3306';
                $database = $dbConfig['database'] ?? 'laravel';
                $username = $dbConfig['username'] ?? 'root';
                $password = $dbConfig['password'] ?? '';

                $connected = false;
                try {
                    DB::connection()->getPdo();
                    $connected = true;
                    $logs[] = "Successfully connected to MySQL database: [{$database}]";
                } catch (Throwable $e) {
                    $logs[] = "Database [{$database}] not found or access pending. Attempting auto-creation...";
                }

                if (!$connected) {
                    try {
                        // Connect to MySQL server without dbname to run CREATE DATABASE IF NOT EXISTS
                        $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        ]);
                        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                        $logs[] = "Database [{$database}] verified/created on MySQL host [{$host}].";
                    } catch (Throwable $pdoEx) {
                        $logs[] = "Notice: Auto-create database step skipped (" . $pdoEx->getMessage() . "). Proceeding to direct connection.";
                    }
                }
            } elseif ($connection === 'sqlite') {
                $dbPath = $dbConfig['database'] ?? database_path('database.sqlite');
                if (!file_exists($dbPath)) {
                    @touch($dbPath);
                    $logs[] = "Created SQLite database file at: {$dbPath}";
                }
            }

            // 2. Run Migrations
            $logs[] = "Running database migrations...";
            Artisan::call('migrate', ['--force' => true]);
            $migrateOutput = Artisan::output();
            $logs[] = trim($migrateOutput) ?: 'All migrations executed successfully.';

            // 3. Run Database Seeders
            $logs[] = "Seeding Lux Glazing CRM datasets (Admin, Trade Prospects, Leads, Quotes, SEO)...";
            Artisan::call('db:seed', ['--force' => true]);
            $seedOutput = Artisan::output();
            $logs[] = trim($seedOutput) ?: 'Seeder completed successfully.';

            // 4. Optimization & Storage link
            try {
                Artisan::call('storage:link');
            } catch (Throwable $ex) {
                // Ignore if symlink already exists or on shared hosting
            }

            try {
                Artisan::call('optimize:clear');
                $logs[] = "System cache & routing optimized.";
            } catch (Throwable $ex) {
                // Ignore
            }

            // Verify created tables
            $tables = [
                'users', 'leads', 'b2b_prospects', 'quotations', 'quotation_items', 
                'email_campaigns', 'tasks_and_reminders', 'seo_keywords', 'marketing_metrics', 'system_settings',
                'marketing_contacts', 'marketing_campaigns', 'marketing_emails', 'marketing_templates', 
                'marketing_email_events', 'marketing_suppressions', 'marketing_consent_logs'
            ];
            $readyTables = [];
            foreach ($tables as $t) {
                if (Schema::hasTable($t)) {
                    $readyTables[] = $t;
                }
            }

        } catch (Throwable $e) {
            $status = 'error';
            $message = 'Setup notice: ' . $e->getMessage();
            $logs[] = "ERROR: " . $e->getMessage();
            $readyTables = [];
        }

        // Return JSON if requested
        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'installed_tables' => $readyTables ?? [],
                'admin_credentials' => [
                    'email' => 'admin@luxglazing.co.uk',
                    'default_password' => 'LuxGlazing@2026',
                    'dashboard_url' => url('/admin/dashboard'),
                ],
                'logs' => $logs,
            ], $status === 'success' ? 200 : 500);
        }

        // Return Luxury HTML Success View
        return view('setup.completed', [
            'status' => $status,
            'message' => $message,
            'tables' => $readyTables ?? [],
            'logs' => $logs,
        ]);
    }
}
