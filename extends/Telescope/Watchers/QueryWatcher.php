<?php

namespace Xin\Telescope\Watchers;

use Illuminate\Database\Events\QueryExecuted;
use Xin\Telescope\EntryType;
use Xin\Telescope\IncomingEntry;
use Xin\Telescope\Telescope;

class QueryWatcher extends Watcher
{
    use FetchesStackTrace;

    /**
     * Register the watcher.
     */
    public function register($app): void
    {
        $app['events']->listen(QueryExecuted::class, [$this, 'recordQuery']);
    }

    /**
     * Record a query was executed.
     *
     * @param QueryExecuted $event
     * @return void
     */
    public function recordQuery(QueryExecuted $event): void
    {
        if (! Telescope::isRecording()) {
            return;
        }

        $time = $event->time;

        if ($caller = $this->getCallerFromStackTrace()) {
            Telescope::record(IncomingEntry::make([
                'connection' => $event->connectionName,
                'sql' => $this->replaceBindings($event),
                'time' => number_format($time, 2, '.', ''),
                'slow' => isset($this->options['slow']) && $time >= $this->options['slow'],
                'file' => $caller['file'],
                'line' => $caller['line'],
            ], EntryType::QUERY));
        }
    }

    /**
     * Calculate the family look-up hash for the query event.
     */
    public function familyHash(QueryExecuted $event): string
    {
        return md5($event->sql);
    }

    /**
     * Format the given bindings to strings.
     */
    protected function formatBindings(QueryExecuted $event): array
    {
        return $event->connection->prepareBindings($event->bindings);
    }

    /**
     * Replace the placeholders with the actual bindings.
     */
    public function replaceBindings(QueryExecuted $event): string
    {
        $sql = $event->sql;

        foreach ($this->formatBindings($event) as $key => $binding) {
            $regex = is_numeric($key)
                ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

            if ($binding === null) {
                $binding = 'null';
            } elseif (! is_int($binding) && ! is_float($binding)) {
                $binding = $this->quoteStringBinding($event, $binding);
            }

            $sql = preg_replace(
                $regex,
                $binding,
                $sql,
                is_numeric($key) ? 1 : -1
            );
        }

        return $sql;
    }

    /**
     * Add quotes to string bindings.
     */
    protected function quoteStringBinding(QueryExecuted $event, string $binding): string
    {
        try {
            $pdo = $event->connection->getPdo();

            if ($pdo instanceof \PDO) {
                return $pdo->quote($binding);
            }
        } catch (\PDOException $e) {
            throw_if('IM001' !== $e->getCode(), $e);
        }

        // Fallback when PDO::quote function is missing...
        $binding = \strtr($binding, [
            chr(26) => '\\Z',
            chr(8) => '\\b',
            '"' => '\"',
            "'" => "\'",
            '\\' => '\\\\',
        ]);

        return "'".$binding."'";
    }
}
