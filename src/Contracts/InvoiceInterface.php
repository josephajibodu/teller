<?php

namespace JosephAjibodu\Teller\Contracts;

interface InvoiceInterface
{
    public function create(array $data);

    public function find(string $invoiceId);

    public function all(array $filters = []);

    public function pay(string $invoiceId);

    public function void(string $invoiceId);
}
