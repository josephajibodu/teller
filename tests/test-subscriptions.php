<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JosephAjibodu\Teller\Teller;
use JosephAjibodu\Teller\Helpers\TellerConfig;

// Configure Teller with your Paystack secret key
TellerConfig::set([
    'default_gateway' => 'paystack',
    'gateways' => [
        'paystack' => [
            'secret_key' => 'sk_test_be0de655ded00e365b892ce24487f8159a1f06fd',
        ],
    ],
]);

echo "💳 Testing Subscription Management\n";
echo "================================\n\n";

try {
    $billing = Teller::make();

    // First, create a customer and plan for testing
    echo "🔧 Setting up test data...\n";

    // Create customer
    $customer = $billing->customers()->create([
        'email' => 'subscription-test-' . time() . '@example.com',
        'first_name' => 'Subscription',
        'last_name' => 'Test',
    ]);
    $customerId = $customer['data']['id'];
    echo "   Customer created: " . $customerId . "\n";

    // Create plan
    $plan = $billing->plans()->create([
        'name' => 'Subscription Test Plan',
        'amount' => 10000, // 100 NGN in kobo
        'interval' => 'monthly',
        'description' => 'Test plan for subscription testing',
    ]);
    $planId = $plan['data']['id'];
    echo "   Plan created: " . $planId . "\n\n";

    // Test 1: Create subscription
    echo "1️⃣ Creating a subscription...\n";
    $subscription = $billing->subscriptions()->create($customerId, [
        'plan' => $planId,
        'authorization' => 'AUTH_test123456789', // Test authorization code
    ]);

    echo "✅ Subscription created successfully!\n";
    echo "   Subscription ID: " . $subscription['data']['id'] . "\n";
    echo "   Status: " . $subscription['data']['status'] . "\n";
    echo "   Plan: " . $subscription['data']['plan']['name'] . "\n";
    echo "   Amount: " . $subscription['data']['amount'] . " kobo\n\n";

    $subscriptionId = $subscription['data']['id'];

    // Test 2: Find specific subscription
    echo "2️⃣ Finding specific subscription...\n";
    $foundSubscription = $billing->subscriptions()->find($subscriptionId);
    echo "✅ Subscription found!\n";
    echo "   Status: " . $foundSubscription['data']['status'] . "\n";
    echo "   Next payment: " . $foundSubscription['data']['next_payment_date'] . "\n\n";

    // Test 3: Pause subscription
    echo "3️⃣ Pausing subscription...\n";
    $pausedSubscription = $billing->subscriptions()->pause($subscriptionId);
    echo "✅ Subscription paused successfully!\n";
    echo "   Status: " . $pausedSubscription['data']['status'] . "\n\n";

    // Test 4: Resume subscription
    echo "4️⃣ Resuming subscription...\n";
    $resumedSubscription = $billing->subscriptions()->resume($subscriptionId);
    echo "✅ Subscription resumed successfully!\n";
    echo "   Status: " . $resumedSubscription['data']['status'] . "\n\n";

    // Test 5: Upgrade subscription
    echo "5️⃣ Testing subscription upgrade...\n";
    $upgradePlan = $billing->plans()->create([
        'name' => 'Upgrade Test Plan',
        'amount' => 20000, // 200 NGN in kobo
        'interval' => 'monthly',
        'description' => 'Upgraded plan for testing',
    ]);

    $upgradedSubscription = $billing->subscriptions()->upgrade($subscriptionId, [
        'plan' => $upgradePlan['data']['id'],
    ]);
    echo "✅ Subscription upgraded successfully!\n";
    echo "   New plan: " . $upgradedSubscription['data']['plan']['name'] . "\n";
    echo "   New amount: " . $upgradedSubscription['data']['amount'] . " kobo\n\n";

    // Test 6: Cancel subscription
    echo "6️⃣ Cancelling subscription...\n";
    $cancelledSubscription = $billing->subscriptions()->cancel($subscriptionId);
    echo "✅ Subscription cancelled successfully!\n";
    echo "   Status: " . $cancelledSubscription['data']['status'] . "\n\n";

    // Cleanup
    echo "🧹 Cleaning up test data...\n";
    $billing->plans()->delete($planId);
    $billing->plans()->delete($upgradePlan['data']['id']);
    $billing->customers()->delete($customerId);
    echo "✅ Cleanup completed!\n\n";

    echo "🎉 All subscription management tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check your Paystack secret key\n";
    echo "2. Ensure you have an active internet connection\n";
    echo "3. Verify your Paystack account is active\n";
    echo "4. Note: Some subscription operations may fail in test mode without real authorization codes\n";
}
