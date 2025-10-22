<?php

namespace JosephAjibodu\Teller\Services;

use JosephAjibodu\Teller\Contracts\GatewayInterface;

class InvoiceService
{
    public function __construct(
        protected GatewayInterface $gateway
    ) {}

    public function create(array $data)
    {
        return $this->gateway->createInvoice($data);
    }

    public function find(string $invoiceId)
    {
        return $this->gateway->getInvoice($invoiceId);
    }

    public function all(array $filters = [])
    {
        return $this->gateway->listInvoices($filters);
    }

    public function pay(string $invoiceId)
    {
        return $this->gateway->payInvoice($invoiceId);
    }

    public function void(string $invoiceId)
    {
        return $this->gateway->voidInvoice($invoiceId);
    }
}
