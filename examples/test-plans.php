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

echo "📋 Plan Management Example\n";
echo "=========================\n\n";

try {
    $billing = Teller::make();

    // Example 1: Create a plan
    echo "1️⃣ Creating a new plan...\n";
    $plan = $billing->plans()->create([
        'name' => 'Test Plan ' . date('Y-m-d H:i:s'),
        'amount' => 25000, // 250 NGN in kobo
        'interval' => 'monthly',
        'description' => 'Test plan created via Teller package',
    ]);

    echo "✅ Plan created successfully!\n";
    echo "   Plan ID: " . $plan['data']['id'] . "\n";
    echo "   Plan Name: " . $plan['data']['name'] . "\n";
    echo "   Amount: " . $plan['data']['amount'] . " kobo\n";
    echo "   Interval: " . $plan['data']['interval'] . "\n\n";

    $planId = $plan['data']['id'];

    // Example 2: Get all plans
    echo "2️⃣ Retrieving all plans...\n";
    $plans = $billing->plans()->all();
    echo "✅ Retrieved " . count($plans['data']) . " plans\n\n";

    // Example 3: Find specific plan
    echo "3️⃣ Finding specific plan...\n";
    $foundPlan = $billing->plans()->find($planId);
    echo "✅ Plan found!\n";
    echo "   Name: " . $foundPlan['data']['name'] . "\n";
    echo "   Amount: " . $foundPlan['data']['amount'] . " kobo\n\n";

    // Example 4: Update plan
    echo "4️⃣ Updating plan...\n";
    $updatedPlan = $billing->plans()->update($planId, [
        'name' => 'Updated Test Plan',
        'description' => 'This plan has been updated',
    ]);
    echo "✅ Plan updated successfully!\n";
    echo "   New name: " . $updatedPlan['data']['name'] . "\n";
    echo "   Description: " . $updatedPlan['data']['description'] . "\n\n";

    // Example 5: Delete plan
    echo "5️⃣ Deleting plan...\n";
    $deleteResult = $billing->plans()->delete($planId);
    echo "✅ Plan deleted successfully!\n\n";

    echo "🎉 Plan management example completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check your Paystack secret key\n";
    echo "2. Ensure you have an active internet connection\n";
    echo "3. Verify your Paystack account is active\n";
}
