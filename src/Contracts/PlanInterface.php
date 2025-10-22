<?php

namespace JosephAjibodu\Teller\Contracts;

interface PlanInterface
{
    public function create(array $data);
    public function all();
    public function find(string $planId);
    public function update(string $planId, array $data);
    public function delete(string $planId);
}
