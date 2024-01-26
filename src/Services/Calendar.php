<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
readonly class Calendar
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
