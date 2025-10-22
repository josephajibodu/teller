<?php

namespace JosephAjibodu\Teller\Contracts;

interface ProrationInterface
{
    public function calculateUpgrade(string $currentPlanId, string $newPlanId, int $daysRemaining): int;

    public function calculateDowngrade(string $currentPlanId, string $newPlanId, int $daysRemaining): int;

    public function calculateCredit(string $planId, int $daysRemaining): int;
}
