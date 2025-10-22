<?php

namespace JosephAjibodu\Teller\Contracts;

interface CustomerInterface
{
    public function create(array $data);

    public function find(string $customerId);

    public function update(string $customerId, array $data);

    public function updateCard(string $customerId, string $cardToken);

    public function delete(string $customerId);
}
