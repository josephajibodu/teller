<?php

namespace JosephAjibodu\Teller\Contracts;

interface SubscriptionInterface
{
    public function create(string $customerId, array $data);

    public function find(string $subscriptionId);

    public function cancel(string $subscriptionId);

    public function resume(string $subscriptionId);

    public function pause(string $subscriptionId);

    public function upgrade(string $subscriptionId, array $data);

    public function downgrade(string $subscriptionId, array $data);
}
