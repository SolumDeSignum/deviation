<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Services;

use ErrorException;
use Illuminate\Log\Events\MessageLogged;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use function explode;
use function storage_path;

use const PHP_EOL;

class DeviationLoggerService
{
    public Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('Deviation');
    }

    public function pushHandler(string $nameLog): DeviationLoggerService
    {
        $this->logger
            ->pushHandler(
                new StreamHandler(
                    storage_path("deviation/log/stacktrace-$nameLog.log"),
                    Logger::DEBUG
                )
            );

        return $this;
    }

    public function log(string $level, string $message, string $stackTrace): DeviationLoggerService
    {
        $this->logger
            ->log(
                $level,
                $message,
                explode(PHP_EOL, $stackTrace)
            );

        return $this;
    }

    public function store(MessageLogged $messageLogged): DeviationLoggerService
    {
        /**
         * @var ErrorException $exception
         */
        $exception = $messageLogged->context['exception'] ?? null;

        $this
            ->pushHandler($messageLogged->nameLog)
            ->log(
                $messageLogged->level ?? null,
                $messageLogged->message ?? null,
                $exception->__toString()
            );

        return $this;
    }
}
