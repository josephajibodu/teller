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

echo "🔗 Testing Fluent API\n";
echo "===================\n\n";

try {
    // Test 1: Basic fluent API
    echo "1️⃣ Testing basic fluent API...\n";
    $billing = Teller::make();
    $plans = $billing->plans()->all();
    echo "✅ Basic fluent API works!\n";
    echo "   Retrieved " . count($plans['data']) . " plans\n\n";

    // Test 2: For user fluent API
    echo "2️⃣ Testing 'for user' fluent API...\n";
    $customer = $billing->customers()->create([
        'email' => 'fluent-test-' . time() . '@example.com',
        'first_name' => 'Fluent',
        'last_name' => 'Test',
    ]);
    $customerId = $customer['data']['id'];

    $userBilling = Teller::for($customerId);
    echo "✅ 'For user' fluent API works!\n";
    echo "   Customer ID: " . $customerId . "\n\n";

    // Test 3: Gateway selection
    echo "3️⃣ Testing gateway selection...\n";
    $paystackBilling = Teller::gateway('paystack');
    $plans = $paystackBilling->plans()->all();
    echo "✅ Gateway selection works!\n";
    echo "   Retrieved " . count($plans['data']) . " plans from Paystack\n\n";

    // Test 4: Chained operations
    echo "4️⃣ Testing chained operations...\n";
    $plan = $paystackBilling->plans()->create([
        'name' => 'Fluent Test Plan',
        'amount' => 15000, // 150 NGN in kobo
        'interval' => 'monthly',
        'description' => 'Test plan created via fluent API',
    ]);
    $planId = $plan['data']['id'];

    echo "✅ Chained operations work!\n";
    echo "   Plan created: " . $planId . "\n\n";

    // Test 5: Complex fluent chain
    echo "5️⃣ Testing complex fluent chain...\n";
    $subscription = Teller::for($customerId)
        ->onGateway('paystack')
        ->subscriptions()
        ->create($customerId, [
            'plan' => $planId,
            'authorization' => 'AUTH_test123456789',
        ]);

    echo "✅ Complex fluent chain works!\n";
    echo "   Subscription created: " . $subscription['data']['id'] . "\n\n";

    // Test 6: Method chaining with different services
    echo "6️⃣ Testing method chaining with different services...\n";

    // Test plans service
    $allPlans = Teller::make()->plans()->all();
    echo "   Plans service: " . count($allPlans['data']) . " plans\n";

    // Test customers service
    $foundCustomer = Teller::make()->customers()->find($customerId);
    echo "   Customers service: Found customer " . $foundCustomer['data']['email'] . "\n";

    // Test subscriptions service
    $foundSubscription = Teller::make()->subscriptions()->find($subscription['data']['id']);
    echo "   Subscriptions service: Found subscription " . $foundSubscription['data']['id'] . "\n";

    // Test invoices service
    $invoice = Teller::make()->invoices()->create([
        'customer' => $customerId,
        'amount' => 5000,
        'description' => 'Test invoice via fluent API',
    ]);
    echo "   Invoices service: Created invoice " . $invoice['data']['id'] . "\n\n";

    // Test 7: Error handling in fluent API
    echo "7️⃣ Testing error handling in fluent API...\n";
    try {
        $invalidSubscription = Teller::for('invalid-customer-id')
            ->subscriptions()
            ->find('invalid-subscription-id');
    } catch (Exception $e) {
        echo "✅ Error handling works: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // Test 8: Static method shortcuts
    echo "8️⃣ Testing static method shortcuts...\n";
    $shortcutPlans = Teller::plans()->all();
    $shortcutCustomers = Teller::customers()->all();

    echo "✅ Static method shortcuts work!\n";
    echo "   Plans via shortcut: " . count($shortcutPlans['data']) . " plans\n";
    echo "   Customers via shortcut: " . count($shortcutCustomers['data']) . " customers\n\n";

    // Test 9: Fluent API with different gateways
    echo "9️⃣ Testing fluent API with different gateways...\n";
    $paystackPlans = Teller::gateway('paystack')->plans()->all();
    echo "✅ Gateway-specific fluent API works!\n";
    echo "   Paystack plans: " . count($paystackPlans['data']) . " plans\n\n";

    // Test 10: Cleanup
    echo "🔟 Testing cleanup...\n";
    $billing->plans()->delete($planId);
    $billing->customers()->delete($customerId);
    echo "✅ Cleanup completed!\n\n";

    echo "🎉 All fluent API tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check your Paystack secret key\n";
    echo "2. Ensure you have an active internet connection\n";
    echo "3. Verify your Paystack account is active\n";
    echo "4. Check that all service classes are properly implemented\n";
}
