<?php

namespace App\Services;

readonly class Calendar
{
    public function isWeekend(\DateTimeInterface|string $date = null): bool
    {
        $date = is_null($date) || is_string($date) ? new \DateTimeImmutable($date) : $date;

        return $date->format('N') > 5;
    }
}
