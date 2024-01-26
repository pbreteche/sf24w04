<?php

namespace App\EventSubscriber\Event;

use App\Entity\Conge;
use Symfony\Contracts\EventDispatcher\Event;

class CongeShowEvent extends Event
{
    public function __construct(private Conge $conge)
    {
    }

    public function getConge(): Conge
    {
        return $this->conge;
    }
}
