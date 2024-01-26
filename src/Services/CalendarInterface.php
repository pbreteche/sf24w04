<?php

namespace App\Services;

interface CalendarInterface
{
    public function isWeekend(\DateTimeInterface|string $date = null): bool;
}
