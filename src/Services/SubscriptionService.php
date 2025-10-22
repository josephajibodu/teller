<?php

namespace JosephAjibodu\Teller\Services;

use JosephAjibodu\Teller\Contracts\GatewayInterface;

class SubscriptionService
{
    public function __construct(
        protected GatewayInterface $gateway
    ) {}

    public function create(string $customerId, array $data)
    {
        return $this->gateway->createSubscription($customerId, $data);
    }

    public function find(string $subscriptionId)
    {
        return $this->gateway->getSubscription($subscriptionId);
    }

    public function cancel(string $subscriptionId)
    {
        return $this->gateway->cancelSubscription($subscriptionId);
    }

    public function resume(string $subscriptionId)
    {
        return $this->gateway->resumeSubscription($subscriptionId);
    }

    public function pause(string $subscriptionId)
    {
        return $this->gateway->pauseSubscription($subscriptionId);
    }

    public function upgrade(string $subscriptionId, array $data)
    {
        return $this->gateway->upgradeSubscription($subscriptionId, $data);
    }

    public function downgrade(string $subscriptionId, array $data)
    {
        return $this->gateway->downgradeSubscription($subscriptionId, $data);
    }
}
