<?php

namespace JosephAjibodu\Teller\Services;

use JosephAjibodu\Teller\Contracts\GatewayInterface;

class CustomerService
{
    public function __construct(
        protected GatewayInterface $gateway
    ) {}

    public function create(array $data)
    {
        return $this->gateway->createCustomer($data);
    }

    public function find(string $customerId)
    {
        return $this->gateway->getCustomer($customerId);
    }

    public function update(string $customerId, array $data)
    {
        return $this->gateway->updateCustomer($customerId, $data);
    }

    public function updateCard(string $customerId, string $cardToken)
    {
        return $this->gateway->updateCard($customerId, $cardToken);
    }

    public function delete(string $customerId)
    {
        return $this->gateway->deleteCustomer($customerId);
    }
}
