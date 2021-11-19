<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Services;

use ErrorException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\View;

use function app;
use function storage_path;

class DeviationPDFLoggerService
{
    /**
     * @throws BindingResolutionException
     */
    public function store(MessageLogged $messageLogged): void
    {
        /**
         * @var ErrorException $exception
         */
        $exception = $messageLogged->context['exception'] ?? null;

        app()
            ->make('dompdf.wrapper')
            ->loadHtml(
                View::make('solumdesignum/deviation::mail.exceptions', [
                    'exception' => $exception ? $exception->__toString() : null
                ])
                    ->render()
            )
            ->setPaper('B4', 'landscape')
            ->save(storage_path("deviation/pdf/stacktrace-$messageLogged->nameLog.pdf"));
    }
}
