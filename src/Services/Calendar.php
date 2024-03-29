<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: 'App\Services\CalendarInterface')]
readonly class Calendar implements CalendarInterface
{
    public function __construct(
        private \DateTimeZone $timezone
    ) {
    }

    public function isWeekend(\DateTimeInterface|string $date = null): bool
    {
        $date = is_null($date) || is_string($date) ? new \DateTimeImmutable($date) : $date;

        return $date->format('N') > 5;
    }
}
