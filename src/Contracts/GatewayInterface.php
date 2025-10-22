<?php

namespace JosephAjibodu\Teller\Contracts;

interface GatewayInterface
{
    // Plans
    public function createPlan(array $data);

    public function getPlans();

    public function findPlan(string $planId);

    public function updatePlan(string $planId, array $data);

    public function deletePlan(string $planId);

    // Customers
    public function createCustomer(array $data);

    public function getCustomer(string $customerId);

    public function updateCustomer(string $customerId, array $data);

    public function updateCard(string $customerId, string $cardToken);

    public function deleteCustomer(string $customerId);

    // Subscriptions
    public function createSubscription(string $customerId, array $data);

    public function getSubscription(string $subscriptionId);

    public function cancelSubscription(string $subscriptionId);

    public function resumeSubscription(string $subscriptionId);

    public function upgradeSubscription(string $subscriptionId, array $data);

    public function downgradeSubscription(string $subscriptionId, array $data);

    public function pauseSubscription(string $subscriptionId);

    // Invoices
    public function createInvoice(array $data);

    public function getInvoice(string $invoiceId);

    public function listInvoices(array $filters = []);

    public function payInvoice(string $invoiceId);

    public function voidInvoice(string $invoiceId);

    // Webhooks
    public function handleWebhook(array $payload);

    public function verifyWebhook(string $signature, string $payload): bool;

    public function getWebhookEventType(array $payload): string;
}
