<?php

namespace JosephAjibodu\Teller\Gateways;

use JosephAjibodu\Teller\Contracts\GatewayInterface;
use JosephAjibodu\Teller\Helpers\HttpClient;
use JosephAjibodu\Teller\Helpers\TellerConfig;

class FlutterwaveGateway implements GatewayInterface
{
    protected HttpClient $httpClient;

    protected string $secretKey;

    protected string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        $this->httpClient = new HttpClient($this->baseUrl, $this->secretKey, [
            'Authorization' => 'Bearer '.$this->secretKey,
            'Content-Type' => 'application/json',
        ]);
    }

    // Plans
    public function createPlan(array $data)
    {
        $response = $this->httpClient->post('/payment-plans', $data);

        return $response['data'] ?? $response;
    }

    public function getPlans()
    {
        $response = $this->httpClient->get('/payment-plans');

        return $response['data'] ?? $response;
    }

    public function findPlan(string $planId)
    {
        $response = $this->httpClient->get("/payment-plans/{$planId}");

        return $response['data'] ?? $response;
    }

    public function updatePlan(string $planId, array $data)
    {
        $response = $this->httpClient->put("/payment-plans/{$planId}", $data);

        return $response['data'] ?? $response;
    }

    public function deletePlan(string $planId)
    {
        $response = $this->httpClient->delete("/payment-plans/{$planId}");

        return $response['data'] ?? $response;
    }

    // Customers
    public function createCustomer(array $data)
    {
        $response = $this->httpClient->post('/customers', $data);

        return $response['data'] ?? $response;
    }

    public function getCustomer(string $customerId)
    {
        $response = $this->httpClient->get("/customers/{$customerId}");

        return $response['data'] ?? $response;
    }

    public function updateCustomer(string $customerId, array $data)
    {
        $response = $this->httpClient->put("/customers/{$customerId}", $data);

        return $response['data'] ?? $response;
    }

    public function updateCard(string $customerId, string $cardToken)
    {
        // Flutterwave doesn't have direct card update API
        // This would typically involve creating a new subscription with the new card
        throw new \Exception('Card update not directly supported by Flutterwave. Use subscription update instead.');
    }

    public function deleteCustomer(string $customerId)
    {
        $response = $this->httpClient->delete("/customers/{$customerId}");

        return $response['data'] ?? $response;
    }

    // Subscriptions
    public function createSubscription(string $customerId, array $data)
    {
        $subscriptionData = array_merge($data, [
            'customer' => $customerId,
        ]);

        $response = $this->httpClient->post('/subscriptions', $subscriptionData);

        return $response['data'] ?? $response;
    }

    public function getSubscription(string $subscriptionId)
    {
        $response = $this->httpClient->get("/subscriptions/{$subscriptionId}");

        return $response['data'] ?? $response;
    }

    public function cancelSubscription(string $subscriptionId)
    {
        $response = $this->httpClient->put("/subscriptions/{$subscriptionId}/cancel", []);

        return $response['data'] ?? $response;
    }

    public function resumeSubscription(string $subscriptionId)
    {
        $response = $this->httpClient->put("/subscriptions/{$subscriptionId}/activate", []);

        return $response['data'] ?? $response;
    }

    public function upgradeSubscription(string $subscriptionId, array $data)
    {
        $response = $this->httpClient->put("/subscriptions/{$subscriptionId}", $data);

        return $response['data'] ?? $response;
    }

    public function downgradeSubscription(string $subscriptionId, array $data)
    {
        $response = $this->httpClient->put("/subscriptions/{$subscriptionId}", $data);

        return $response['data'] ?? $response;
    }

    public function pauseSubscription(string $subscriptionId)
    {
        $response = $this->httpClient->put("/subscriptions/{$subscriptionId}/pause", []);

        return $response['data'] ?? $response;
    }

    // Invoices
    public function createInvoice(array $data)
    {
        $response = $this->httpClient->post('/invoices', $data);

        return $response['data'] ?? $response;
    }

    public function getInvoice(string $invoiceId)
    {
        $response = $this->httpClient->get("/invoices/{$invoiceId}");

        return $response['data'] ?? $response;
    }

    public function listInvoices(array $filters = [])
    {
        $query = http_build_query($filters);
        $response = $this->httpClient->get("/invoices?{$query}");

        return $response['data'] ?? $response;
    }

    public function payInvoice(string $invoiceId)
    {
        $response = $this->httpClient->post("/invoices/{$invoiceId}/pay", []);

        return $response['data'] ?? $response;
    }

    public function voidInvoice(string $invoiceId)
    {
        $response = $this->httpClient->put("/invoices/{$invoiceId}/void", []);

        return $response['data'] ?? $response;
    }

    // Webhooks
    public function handleWebhook(array $payload)
    {
        return $this->verifyWebhook($payload['signature'] ?? '', json_encode($payload));
    }

    public function verifyWebhook(string $signature, string $payload): bool
    {
        $secretHash = TellerConfig::get('flutterwave.webhook_secret');
        $expectedSignature = hash_hmac('sha256', $payload, $secretHash);

        return hash_equals($expectedSignature, $signature);
    }

    public function getWebhookEventType(array $payload): string
    {
        return $payload['event'] ?? 'unknown';
    }
}
