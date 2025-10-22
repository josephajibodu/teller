<?php

require_once __DIR__.'/../vendor/autoload.php';

use JosephAjibodu\Teller\Teller;

// Initialize Teller with default gateway (Paystack)
$billing = Teller::make();

// Or specify a gateway
$billing = Teller::gateway('flutterwave');

// Or use the fluent API
$billing = Teller::for($user)->onGateway('paystack');

// ===========================================
// 1. PLAN MANAGEMENT
// ===========================================

// Create a plan
$plan = $billing->plans()->create([
    'name' => 'Pro Plan',
    'amount' => 25000, // 250 NGN in kobo
    'interval' => 'monthly',
    'description' => 'Access to premium features',
]);

// Get all plans
$plans = $billing->plans()->all();

// Get a specific plan
$plan = $billing->plans()->find('pro-plan-id');

// Update a plan
$billing->plans()->update('pro-plan-id', [
    'amount' => 30000,
]);

// Delete a plan
$billing->plans()->delete('pro-plan-id');

// ===========================================
// 2. CUSTOMER MANAGEMENT
// ===========================================

// Create a customer
$customer = $billing->customers()->create([
    'email' => 'joseph@example.com',
    'name' => 'Joseph Ajibodu',
    'metadata' => ['team_id' => 4],
]);

// Get a customer
$customer = $billing->customers()->find('customer-id');

// Update customer
$billing->customers()->update('customer-id', [
    'name' => 'Joseph Updated',
]);

// Update customer's card
$billing->customers()->updateCard('customer-id', 'new-card-token');

// ===========================================
// 3. SUBSCRIPTION LIFECYCLE
// ===========================================

// Create a subscription
$subscription = $billing->subscriptions()->create('customer-id', [
    'plan_id' => 'pro-monthly',
    'trial_days' => 7,
]);

// Get a subscription
$subscription = $billing->subscriptions()->find('subscription-id');

// Cancel a subscription
$billing->subscriptions()->cancel('subscription-id');

// Resume a subscription (if within grace period)
$billing->subscriptions()->resume('subscription-id');

// Pause a subscription
$billing->subscriptions()->pause('subscription-id');

// ===========================================
// 4. UPGRADE/DOWNGRADE WITH PRORATION
// ===========================================

// Upgrade with proration (charge immediately)
$billing->subscriptions()->upgrade('subscription-id', [
    'to_plan' => 'business-monthly',
    'prorate' => true,
    'immediate' => true,
]);

// Schedule upgrade for next billing cycle
$billing->subscriptions()->upgrade('subscription-id', [
    'to_plan' => 'business-monthly',
    'prorate' => false,
    'effective' => 'next_cycle',
]);

// Downgrade with proration
$billing->subscriptions()->downgrade('subscription-id', [
    'to_plan' => 'basic-monthly',
    'prorate' => true,
]);

// ===========================================
// 5. INVOICE MANAGEMENT
// ===========================================

// Create an invoice
$invoice = $billing->invoices()->create([
    'customer_id' => 'customer-id',
    'amount' => 5000, // 50 NGN in kobo
    'description' => 'Extra storage space',
]);

// Get an invoice
$invoice = $billing->invoices()->find('invoice-id');

// List invoices for a customer
$invoices = $billing->invoices()->all(['customer_id' => 'customer-id']);

// Pay an invoice
$billing->invoices()->pay('invoice-id');

// Void an invoice
$billing->invoices()->void('invoice-id');

// ===========================================
// 6. WEBHOOK HANDLING
// ===========================================

// In Laravel, you would typically do this in a controller:
/*
Route::post('/billing/webhook', [BillingWebhookController::class, 'handle']);

class BillingWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = Teller::webhooks()->handle($request->all());

        switch ($event->type) {
            case 'subscription.created':
                // Handle new subscription
                break;
            case 'subscription.renewed':
                // Handle subscription renewal
                break;
            case 'subscription.upgraded':
                // Handle subscription upgrade
                break;
            case 'subscription.cancelled':
                // Handle subscription cancellation
                break;
            case 'payment.failed':
                // Handle failed payment
                break;
        }
    }
}
*/

// ===========================================
// 7. FLUENT API EXAMPLES
// ===========================================

// Chain operations for better readability
Teller::for($user)
    ->onGateway('paystack')
    ->subscriptions()
    ->upgrade('subscription-id', [
        'to_plan' => 'premium-monthly',
        'prorate' => true,
    ]);

// ===========================================
// 8. ERROR HANDLING
// ===========================================

try {
    $subscription = $billing->subscriptions()->create('customer-id', [
        'plan_id' => 'invalid-plan',
    ]);
} catch (\Exception $e) {
    // Handle gateway-specific errors
    echo 'Error: '.$e->getMessage();
}

// ===========================================
// 9. CONFIGURATION
// ===========================================

// Set configuration (if not using Laravel)
TellerConfig::set([
    'default_gateway' => 'flutterwave',
    'gateways' => [
        'paystack' => ['secret' => 'sk_test_...'],
        'flutterwave' => ['secret' => 'FLWSECK_TEST-...'],
    ],
    'proration' => [
        'enabled' => true,
        'rounding' => 'up',
    ],
]);

// ===========================================
// 10. MONEY HELPER EXAMPLES
// ===========================================

use JosephAjibodu\Teller\Support\Money;

// Create money objects
$amount = Money::fromNaira(250.00); // 250 NGN
$amount = Money::fromKobo(25000); // 250 NGN in kobo

// Perform calculations
$total = $amount->add(Money::fromNaira(50.00)); // 300 NGN
$discount = $amount->multiply(0.1); // 10% discount
$formatted = $amount->format(); // "250.00 NGN"

// ===========================================
// 11. DATE HELPER EXAMPLES
// ===========================================

use JosephAjibodu\Teller\Support\DateHelper;

$date = new \DateTime;

// Get billing information
$daysInMonth = DateHelper::daysInMonth($date);
$daysRemaining = DateHelper::daysRemainingInMonth($date);
$nextBilling = DateHelper::nextBillingDate($date, 'monthly');

echo "Days remaining in month: {$daysRemaining}";
echo 'Next billing date: '.$nextBilling->format('Y-m-d');
