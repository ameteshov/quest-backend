<?php

namespace Tests\Support;

use Illuminate\Support\Facades\DB;

trait LoadDumpTrait
{
    protected static $tables;

    public function prepareDatabase()
    {
        if ($this->isFirstTest()) {
            $scheme = config('database.default');
            $tables = $this->getTables();
            $except = ['migrations', 'roles'];

            $this->clearDatabase($scheme, $tables, $except);

            $dump = $this->getSql();

            DB::unprepared($dump);

            if ($scheme === 'pgsql') {
                $this->prepareSequences($tables, $except);
            }
        }
    }

    public function loadTestDump(string $filename)
    {
        $path = base_path("tests/Fixtures/{$this->getTestClassName()}/$filename");

        if (!is_file($path) || !is_readable($path)) {
            throw new \LogicException("File path {$path} not readable or not exists");
        }

        $content = file_get_contents($path);

        DB::unprepared($content);
    }

    public function getSql(): string
    {
        $dumpPath = base_path('tests/Fixtures/dump.sql');

        if (empty($dumpPath)) {
            throw new \LogicException('Dump file path is empty');
        }

        if (!is_file($dumpPath) || !is_readable($dumpPath)) {
            throw new \LogicException("File {$dumpPath} not exists or not readable");
        }

        return file_get_contents($dumpPath);
    }

    protected function clearDatabase(string $scheme, array $tables, ?array $except = ['migrations']): void
    {
        if ($scheme === 'pgsql') {
            $query = $this->getClearPsqlDatabaseQuery($tables, $except);
        } elseif ($scheme === 'mysql') {
            $query = $this->getClearMySQLDatabaseQuery($tables, $except);
        }

        if (!empty($query)) {
            app('db.connection')->unprepared($query);
        }

        if ($scheme === 'pgsql') {
            $this->prepareSequences($tables, $except);
        }
    }

    protected function isFirstTest(): bool
    {
        return (int)$this->getTestResultObject()->time() === 0;
    }

    protected function getTables()
    {
        if (empty(self::$tables)) {
            self::$tables = app('db.connection')
                ->getDoctrineSchemaManager()
                ->listTableNames();
        }

        return self::$tables;
    }

    public function getClearPsqlDatabaseQuery($tables, $except = ['migrations'])
    {
        return $this->arrayCallbacksConcatResults($tables, function ($table) use ($except) {
            if (in_array($table, $except)) {
                return '';
            } else {
                return "TRUNCATE {$table} RESTART IDENTITY CASCADE; \n";
            }
        });
    }

    public function getClearMySQLDatabaseQuery($tables, $except = ['migrations'])
    {
        $query = "SET FOREIGN_KEY_CHECKS = 0;\n";

        $query .= $this->arrayCallbacksConcatResults($tables, function ($table) use ($except) {
            if (in_array($table, $except)) {
                return '';
            } else {
                return "TRUNCATE TABLE {$table}; \n";
            }
        });

        $query .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        return $query;
    }

    public function prepareSequences($tables, $except)
    {
        $query = $this->arrayCallbacksConcatResults($tables, function ($table) use ($except) {
            if (in_array($table, $except)) {
                return '';
            } else {
                return "SELECT setval('{$table}_id_seq', (select max(id) from {$table}));\n";
            }
        });

        app('db.connection')->unprepared($query);
    }

    protected function arrayCallbacksConcatResults($array, $callback) {
        $content = '';

        foreach ($array as $key => $value) {
            $content .= $callback($value, $key);
        }

        return $content;
    }
}
