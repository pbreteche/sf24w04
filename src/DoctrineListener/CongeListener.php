<?php

namespace App\DoctrineListener;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event as ORMEvent;

#[AsEntityListener(entity: Conge::class)]
class CongeListener
{
    public function postUpdate(Conge $conge, ORMEvent\PostUpdateEventArgs $event): void
    {
        dump($conge);
    }
    public function preUpdate(Conge $conge, ORMEvent\PreUpdateEventArgs $event): void
    {
        $event->getNewValue('debut');
        $event->getOldValue('debut');
        $event->getEntityChangeSet();
    }
}