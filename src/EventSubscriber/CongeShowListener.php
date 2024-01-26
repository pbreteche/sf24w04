<?php

namespace App\EventSubscriber;

use App\EventSubscriber\Event\CongeShowEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: CongeShowEvent::class)]
class CongeShowListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(CongeShowEvent $event)
    {
        $this->logger->info('conge show avec listener');
    }
}
