<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SandboxController extends Controller
{
    private array $protectedTables = [
        'users',
        'migrations',
        'password_resets',
        'failed_jobs',
        'personal_access_tokens',
        'permissions',
        'roles',
        'role_has_permissions',
        'model_has_roles',
        'model_has_permissions',
        'jobs',
    ];

    public function index(): View
    {
        abort_unless($this->sandboxEnabled(), 404);
        $tables = $this->listTruncatableTables();
        $approxRecords = collect($tables)->mapWithKeys(fn($table) => [$table => DB::table($table)->count()]);

        return view('admin.sandbox.purge', [
            'tables' => $tables,
            'approxRecords' => $approxRecords,
        ]);
    }

    public function purge(Request $request): RedirectResponse
    {
        abort_unless($this->sandboxEnabled(), 404);

        $request->validate([
            'confirmation' => 'required|accepted',
        ]);

        $driver = DB::getDriverName();
        $tables = $this->listTruncatableTables();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif ($driver === 'pgsql') {
            DB::statement('SET session_replication_role = replica');
        }

        foreach ($tables as $table) {
            if ($driver === 'sqlite') {
                DB::table($table)->delete();
                DB::statement("DELETE FROM sqlite_sequence WHERE name = '{$table}'");
            } else {
                DB::table($table)->truncate();
            }
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } elseif ($driver === 'pgsql') {
            DB::statement('SET session_replication_role = DEFAULT');
        }

        return back()->with('success', 'All sandbox data (except users) has been purged.');
    }

    private function listTruncatableTables(): array
    {
        $exclude = array_map(fn($table) => DB::getTablePrefix() . $table, $this->protectedTables);
        $tables = $this->allTableNames();

        return collect($tables)
            ->reject(fn($table) => in_array($table, $exclude, true))
            ->values()
            ->all();
    }

    private function sandboxEnabled(): bool
    {
        return config('app.sandbox_mode') || app()->environment('local');
    }

    private function allTableNames(): array
    {
        $connection = DB::connection();

        if (method_exists($connection, 'getDoctrineDriver') && class_exists(\Doctrine\DBAL\Schema\AbstractSchemaManager::class)) {
            return $connection->getDoctrineSchemaManager()->listTableNames();
        }

        $driver = $connection->getDriverName();

        return match ($driver) {
            'sqlite' => collect(DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"))
                ->pluck('name')
                ->all(),
            'mysql' => collect(DB::select('SHOW TABLES'))
                ->map(fn($row) => array_values((array) $row)[0] ?? null)
                ->filter()
                ->all(),
            'pgsql' => collect(DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'"))
                ->pluck('tablename')
                ->all(),
            default => [],
        };
    }
}

