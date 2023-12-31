<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Listeners;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Notification;
use SolumDeSignum\Deviation\Actions\DirectoriesAction;
use SolumDeSignum\Deviation\DataTransferObject\DeviationReporterData;
use SolumDeSignum\Deviation\Notifications\DeviationLoggedNotification;
use SolumDeSignum\Deviation\Services\DeviationLoggerService;
use SolumDeSignum\Deviation\Services\DeviationPDFLoggerService;

use function config;
use function now;

class DeviationLoggedListener
{
    private DeviationReporterData $deviationReporterData;

    private DeviationPDFLoggerService $deviationPDFLoggerService;

    private DeviationLoggerService $deviationLoggerService;

    private DirectoriesAction $directoriesAction;

    public function __construct()
    {
        $this->deviationReporterData = new DeviationReporterData();
        $this->deviationPDFLoggerService = new DeviationPDFLoggerService();
        $this->deviationLoggerService = new DeviationLoggerService();
        $this->directoriesAction = new DirectoriesAction();
    }

    /**
     * Handle the event.
     *
     * @param MessageLogged $event
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(MessageLogged $event): void
    {
        $event->nameLog = now()
            ->format('Y-m-d H:i:s');

        if (config('deviation.logger.api')) {
            $this->deviationReporterData
                ->run($event);
        }

        if (config('deviation.logger.pdf') || config('deviation.logger.log')) {
            $this->directoriesAction
                ->run();
        }

        if (config('deviation.logger.pdf')) {
            $this->deviationPDFLoggerService
                ->store($event);
        }

        if (config('deviation.logger.log')) {
            $this->deviationLoggerService
                ->store($event);
        }

        if (config('deviation.notifications.enabled')) {
            $user = new User();
            $user->setAttribute('email', config('deviation.notifications.notifiable.to'));

            Notification::send(
                $user,
                new DeviationLoggedNotification($event)
            );
        }
    }
}
