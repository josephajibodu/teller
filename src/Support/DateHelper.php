<?php

namespace JosephAjibodu\Teller\Support;

class DateHelper
{
    public static function daysInMonth(\DateTimeInterface $date): int
    {
        return (int) $date->format('t');
    }

    public static function daysRemainingInMonth(\DateTimeInterface $date): int
    {
        $lastDayOfMonth = (int) $date->format('t');
        $currentDay = (int) $date->format('j');

        return $lastDayOfMonth - $currentDay + 1;
    }

    public static function daysElapsedInMonth(\DateTimeInterface $date): int
    {
        return (int) $date->format('j');
    }

    public static function nextBillingDate(\DateTimeInterface $date, string $interval = 'monthly'): \DateTime
    {
        $newDate = new \DateTime($date->format('Y-m-d H:i:s'));

        return match ($interval) {
            'monthly' => $newDate->modify('+1 month'),
            'yearly' => $newDate->modify('+1 year'),
            'weekly' => $newDate->modify('+1 week'),
            'daily' => $newDate->modify('+1 day'),
            default => $newDate->modify('+1 month'),
        };
    }

    public static function isSameDay(\DateTimeInterface $date1, \DateTimeInterface $date2): bool
    {
        return $date1->format('Y-m-d') === $date2->format('Y-m-d');
    }

    public static function isSameMonth(\DateTimeInterface $date1, \DateTimeInterface $date2): bool
    {
        return $date1->format('Y-m') === $date2->format('Y-m');
    }

    public static function startOfDay(\DateTimeInterface $date): \DateTime
    {
        $newDate = new \DateTime($date->format('Y-m-d H:i:s'));

        return $newDate->setTime(0, 0, 0);
    }

    public static function endOfDay(\DateTimeInterface $date): \DateTime
    {
        $newDate = new \DateTime($date->format('Y-m-d H:i:s'));

        return $newDate->setTime(23, 59, 59);
    }

    public static function startOfMonth(\DateTimeInterface $date): \DateTime
    {
        $newDate = new \DateTime($date->format('Y-m-d H:i:s'));

        return $newDate->modify('first day of this month')->setTime(0, 0, 0);
    }

    public static function endOfMonth(\DateTimeInterface $date): \DateTime
    {
        $newDate = new \DateTime($date->format('Y-m-d H:i:s'));

        return $newDate->modify('last day of this month')->setTime(23, 59, 59);
    }
}
