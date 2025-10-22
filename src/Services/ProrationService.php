<?php

namespace JosephAjibodu\Teller\Services;

use DateTime;

class ProrationService
{
    public function calculate(array $currentPlan, array $newPlan, DateTime $startDate, int $cycleDays): float
    {
        $daysUsed = (new DateTime)->diff($startDate)->days;
        $unusedRatio = ($cycleDays - $daysUsed) / $cycleDays;
        $priceDifference = $newPlan['amount'] - $currentPlan['amount'];

        $diff = $priceDifference * $unusedRatio;

        return max(0, round($diff, 2));
    }
}
