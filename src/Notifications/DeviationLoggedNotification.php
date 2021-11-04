<?php

declare(strict_types=1);

namespace SolumDesignum\Deviation\Notifications;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SolumDeSignum\Deviation\Actions\DirectoriesAction;

use function config;
use function storage_path;

class DeviationLoggedNotification extends Notification
{
    private MessageLogged $messagedLogged;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MessageLogged $messageLogged)
    {
        $this->messagedLogged = $messageLogged;
        (new DirectoriesAction())
            ->run();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $nameLog = $this->messagedLogged->nameLog;

        $mailMessage = (new MailMessage())
            ->priority(config('deviation.notifications.priority'))
            ->level($this->messagedLogged->level)
            ->cc(config('deviation.notifications.notifiable.cc'))
            ->bcc(config('deviation.notifications.notifiable.bcc'))
            ->subject(config('app.url') . ": Whoops! stacktrace-$nameLog")
            ->greeting($this->messagedLogged->message);

        if (config('deviation.notifications.attachments.stack_trace') && config('deviation.logger.pdf')) {
            $mailMessage->attach(storage_path("deviation/pdf/stacktrace-$nameLog.pdf"));
        }

        if (config('deviation.notifications.attachments.stack_trace') && config('deviation.logger.log')) {
            $mailMessage->attach(storage_path("deviation/log/stacktrace-$nameLog.log"));
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
