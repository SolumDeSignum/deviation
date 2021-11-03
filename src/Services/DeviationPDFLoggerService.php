<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Services;

use Barryvdh\DomPDF\Facade as PDF;
use ErrorException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\View;

use function storage_path;

class DeviationPDFLoggerService
{
    public function store(MessageLogged $messageLogged): void
    {
        /**
         * @var ErrorException $exception
         */
        $exception = $messageLogged->context['exception'];

        PDF::loadView(
            View::make('solumdesignum/deviation::mail.exceptions', [
                'exception' => $exception->__toString()
            ])
                ->render()
        )
            ->setPaper('B4', 'landscape')
            ->save(storage_path("deviation/message-logged-$messageLogged->nameLog.pdf"));
    }
}
