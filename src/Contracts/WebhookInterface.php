<?php

namespace JosephAjibodu\Teller\Contracts;

interface WebhookInterface
{
    public function handle(array $payload);

    public function verify(string $signature, string $payload);

    public function getEventType(array $payload): string;
}
