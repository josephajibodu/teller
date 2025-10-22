<?php

require_once __DIR__.'/../vendor/autoload.php';

use JosephAjibodu\Teller\Helpers\TellerConfig;
use JosephAjibodu\Teller\Teller;

// Configure Teller with your Paystack secret key
TellerConfig::set([
    'default_gateway' => 'paystack',
    'gateways' => [
        'paystack' => [
            'secret_key' => 'sk_test_be0de655ded00e365b892ce24487f8159a1f06fd',
        ],
    ],
]);

echo "👤 Testing Customer Management\n";
echo "============================\n\n";

try {
    $billing = Teller::make();

    // Test 1: Create a customer
    echo "1️⃣ Creating a new customer...\n";
    $customer = $billing->customers()->create([
        'email' => 'test-'.time().'@example.com',
        'first_name' => 'Test',
        'last_name' => 'Customer',
        'metadata' => [
            'test' => true,
            'created_at' => date('Y-m-d H:i:s'),
        ],
    ]);

    echo "✅ Customer created successfully!\n";
    echo '   Customer ID: '.$customer['data']['id']."\n";
    echo '   Email: '.$customer['data']['email']."\n";
    echo '   Name: '.$customer['data']['first_name'].' '.$customer['data']['last_name']."\n";
    echo '   Created: '.$customer['data']['createdAt']."\n\n";

    $customerId = $customer['data']['id'];

    // Test 2: Find specific customer
    echo "2️⃣ Finding specific customer...\n";
    $foundCustomer = $billing->customers()->find($customerId);
    echo "✅ Customer found!\n";
    echo '   Email: '.$foundCustomer['data']['email']."\n";
    echo '   Name: '.$foundCustomer['data']['first_name'].' '.$foundCustomer['data']['last_name']."\n\n";

    // Test 3: Update customer
    echo "3️⃣ Updating customer...\n";
    $updatedCustomer = $billing->customers()->update($customerId, [
        'first_name' => 'Updated',
        'last_name' => 'Customer',
        'metadata' => [
            'test' => true,
            'updated_at' => date('Y-m-d H:i:s'),
        ],
    ]);
    echo "✅ Customer updated successfully!\n";
    echo '   New name: '.$updatedCustomer['data']['first_name'].' '.$updatedCustomer['data']['last_name']."\n\n";

    // Test 4: Update customer card (this will fail in test mode, but shows the API)
    echo "4️⃣ Testing card update (will fail in test mode)...\n";
    try {
        $billing->customers()->updateCard($customerId, 'AUTH_test123');
        echo "✅ Card update successful!\n";
    } catch (Exception $e) {
        echo '⚠️  Card update failed (expected in test mode): '.$e->getMessage()."\n";
    }
    echo "\n";

    // Test 5: Delete customer
    echo "5️⃣ Deleting customer...\n";
    $deleteResult = $billing->customers()->delete($customerId);
    echo "✅ Customer deleted successfully!\n\n";

    echo "🎉 All customer management tests passed!\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check your Paystack secret key\n";
    echo "2. Ensure you have an active internet connection\n";
    echo "3. Verify your Paystack account is active\n";
}
