<?php

namespace JosephAjibodu\Teller\Services;

use JosephAjibodu\Teller\Contracts\GatewayInterface;

class WebhookService
{
    public function __construct(
        protected GatewayInterface $gateway
    ) {}

    public function handle(array $payload)
    {
        return $this->gateway->handleWebhook($payload);
    }

    public function verify(string $signature, string $payload)
    {
        return $this->gateway->verifyWebhook($signature, $payload);
    }

    public function getEventType(array $payload): string
    {
        return $this->gateway->getWebhookEventType($payload);
    }
}
