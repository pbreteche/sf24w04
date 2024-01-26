<?php

namespace App\EventSubscriber;

use App\EventSubscriber\Event\AppEvent;
use App\EventSubscriber\Event\CongeShowEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

readonly class CongeShowSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function onCongeShow(Event $event): void
    {
        $this->logger->info('conge show');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppEvent::CONGE_SHOW_EVENT => 'onCongeShow',
            CongeShowEvent::class => 'onCongeShow',
        ];
    }
}
