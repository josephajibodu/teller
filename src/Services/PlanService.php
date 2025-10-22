<?php

namespace JosephAjibodu\Teller\Services;

use JosephAjibodu\Teller\Contracts\GatewayInterface;

class PlanService
{
    public function __construct(
        protected GatewayInterface $gateway
    ) {}

    public function create(array $data)
    {
        return $this->gateway->createPlan($data);
    }

    public function all()
    {
        return $this->gateway->getPlans();
    }

    public function find(string $planId)
    {
        return $this->gateway->findPlan($planId);
    }

    public function update(string $planId, array $data)
    {
        return $this->gateway->updatePlan($planId, $data);
    }

    public function delete(string $planId)
    {
        return $this->gateway->deletePlan($planId);
    }
}
