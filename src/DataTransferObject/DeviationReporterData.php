<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\DataTransferObject;

use Exception;
use Illuminate\Log\Events\MessageLogged;

use function explode;

class DeviationReporterData
{
    public ?string $message;

    public ?int $code;

    public ?int $line;

    public ?string $file;

    public ?int $severity;

    public array $blade = [
        'view' => null,
        'path' => '',
        'model' => [
            'class' => null,
            'data' => null,
        ],
    ];

    public ?array $stacktrace;

    public function setMessage(?string $message): DeviationReporterData
    {
        $this->message = $message;

        return $this;
    }

    public function setCode(?int $code): DeviationReporterData
    {
        $this->code = $code;

        return $this;
    }

    public function setLine(?int $line): DeviationReporterData
    {
        $this->line = $line;

        return $this;
    }

    public function setFile(?string $file): DeviationReporterData
    {
        $this->file = $file;

        return $this;
    }

    public function setSeverity(?int $severity): DeviationReporterData
    {
        $this->severity = $severity;

        return $this;
    }

    public function setStacktrace(?array $stacktrace): DeviationReporterData
    {
        $this->stacktrace = $stacktrace;

        return $this;
    }

    public function run(MessageLogged $messageLogged): DeviationReporterData
    {
        /**
         * @var Exception $exception
         */
        $exception = $messageLogged->context['exception'];

        $this->setCode($exception->getCode())
            ->setFile($exception->getFile())
            ->setLine($exception->getLine())
            ->setMessage($messageLogged->message)
            ->setSeverity($exception->getSeverity())
            ->setStacktrace(explode("\n\n", $exception->__toString()));

        return $this;
    }
}
