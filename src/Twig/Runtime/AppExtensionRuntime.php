<?php

namespace App\Twig\Runtime;

use App\Services\CalendarInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly CalendarInterface $calendar,
    ) {
    }

    public function doSomething($value)
    {
        // ...
    }

    public function weekendTest($value): bool
    {
        return $this->calendar->isWeekend($value);
    }
}
