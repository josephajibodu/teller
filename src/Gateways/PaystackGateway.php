<?php

namespace JosephAjibodu\Teller\Gateways;

use JosephAjibodu\Teller\Contracts\GatewayInterface;
use JosephAjibodu\Teller\Helpers\HttpClient;

class PaystackGateway implements GatewayInterface
{
    protected string $secret;

    protected HttpClient $client;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
        $this->client = new HttpClient('https://api.paystack.co', $secret, [
            'Authorization' => 'Bearer '.$secret,
            'Content-Type' => 'application/json',
        ]);
    }

    // Plans
    public function createPlan(array $data)
    {
        return $this->client->post('/plan', $data);
    }

    public function getPlans()
    {
        return $this->client->get('/plan');
    }

    public function findPlan(string $planId)
    {
        return $this->client->get("/plan/{$planId}");
    }

    public function updatePlan(string $planId, array $data)
    {
        return $this->client->put("/plan/{$planId}", $data);
    }

    public function deletePlan(string $planId)
    {
        return $this->client->delete("/plan/{$planId}");
    }

    // Customers
    public function createCustomer(array $data)
    {
        return $this->client->post('/customer', $data);
    }

    public function getCustomer(string $customerId)
    {
        return $this->client->get("/customer/{$customerId}");
    }

    public function updateCustomer(string $customerId, array $data)
    {
        return $this->client->put("/customer/{$customerId}", $data);
    }

    public function updateCard(string $customerId, string $cardToken)
    {
        return $this->client->put("/customer/{$customerId}", [
            'authorization_code' => $cardToken,
        ]);
    }

    public function deleteCustomer(string $customerId)
    {
        return $this->client->delete("/customer/{$customerId}");
    }

    // Subscriptions
    public function createSubscription(string $customerId, array $data)
    {
        $subscriptionData = array_merge($data, [
            'customer' => $customerId,
        ]);

        return $this->client->post('/subscription', $subscriptionData);
    }

    public function getSubscription(string $subscriptionId)
    {
        return $this->client->get("/subscription/{$subscriptionId}");
    }

    public function cancelSubscription(string $subscriptionId)
    {
        return $this->client->post('/subscription/disable', [
            'code' => $subscriptionId,
            'token' => $this->secret,
        ]);
    }

    public function resumeSubscription(string $subscriptionId)
    {
        return $this->client->post('/subscription/enable', [
            'code' => $subscriptionId,
            'token' => $this->secret,
        ]);
    }

    public function upgradeSubscription(string $subscriptionId, array $data)
    {
        return $this->client->put("/subscription/{$subscriptionId}", $data);
    }

    public function downgradeSubscription(string $subscriptionId, array $data)
    {
        return $this->client->put("/subscription/{$subscriptionId}", $data);
    }

    public function pauseSubscription(string $subscriptionId)
    {
        return $this->client->post('/subscription/disable', [
            'code' => $subscriptionId,
            'token' => $this->secret,
        ]);
    }

    // Invoices
    public function createInvoice(array $data)
    {
        return $this->client->post('/paymentrequest', $data);
    }

    public function getInvoice(string $invoiceId)
    {
        return $this->client->get("/paymentrequest/{$invoiceId}");
    }

    public function listInvoices(array $filters = [])
    {
        $query = http_build_query($filters);

        return $this->client->get("/paymentrequest?{$query}");
    }

    public function payInvoice(string $invoiceId)
    {
        return $this->client->post("/paymentrequest/{$invoiceId}/archive");
    }

    public function voidInvoice(string $invoiceId)
    {
        return $this->client->post("/paymentrequest/{$invoiceId}/archive");
    }

    // Webhooks
    public function handleWebhook(array $payload)
    {
        return $this->verifyWebhook($payload['signature'] ?? '', json_encode($payload));
    }

    public function verifyWebhook(string $signature, string $payload): bool
    {
        $secret = $this->secret;
        $expectedSignature = hash_hmac('sha512', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }

    public function getWebhookEventType(array $payload): string
    {
        return $payload['event'] ?? 'unknown';
    }
}
