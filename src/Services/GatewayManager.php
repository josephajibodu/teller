<?php

namespace JosephAjibodu\Teller\Services;

use JosephAjibodu\Teller\Gateways\GatewayFactory;

class GatewayManager
{
    protected ?string $gatewayName = null;
    protected $gateway;
    protected $user;

    public function __construct(?string $gatewayName = null)
    {
        $this->useGateway($gatewayName ?? 'paystack');
    }

    public function useGateway(string $name): static
    {
        $this->gatewayName = $name;
        $this->gateway = GatewayFactory::make($name);
        return $this;
    }

    public function forUser($user): static
    {
        $this->user = $user;
        return $this;
    }

    public function plans() { return new PlanService($this->gateway); }
    public function customers() { return new CustomerService($this->gateway); }
    public function subscriptions() { return new SubscriptionService($this->gateway); }
    public function invoices() { return new InvoiceService($this->gateway); }
    public function webhooks() { return new WebhookService($this->gateway); }
}