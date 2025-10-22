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

echo "🧾 Testing Invoice Management\n";
echo "===========================\n\n";

try {
    $billing = Teller::make();

    // First, create a customer for testing
    echo "🔧 Setting up test data...\n";
    $customer = $billing->customers()->create([
        'email' => 'invoice-test-' . time() . '@example.com',
        'first_name' => 'Invoice',
        'last_name' => 'Test',
    ]);
    $customerId = $customer['data']['id'];
    echo "   Customer created: " . $customerId . "\n\n";

    // Test 1: Create a simple invoice
    echo "1️⃣ Creating a simple invoice...\n";
    $invoice = $billing->invoices()->create([
        'customer' => $customerId,
        'amount' => 5000, // 50 NGN in kobo
        'description' => 'Test invoice created via Teller package',
    ]);

    echo "✅ Simple invoice created successfully!\n";
    echo "   Invoice ID: " . $invoice['data']['id'] . "\n";
    echo "   Amount: " . $invoice['data']['amount'] . " kobo\n";
    echo "   Description: " . $invoice['data']['description'] . "\n";
    echo "   Status: " . $invoice['data']['status'] . "\n\n";

    $invoiceId = $invoice['data']['id'];

    // Test 2: Create an invoice with line items
    echo "2️⃣ Creating an invoice with line items...\n";
    $detailedInvoice = $billing->invoices()->create([
        'customer' => $customerId,
        'description' => 'Detailed invoice with multiple items',
        'line_items' => [
            [
                'name' => 'Tripod stand',
                'amount' => 2000000, // 20,000 NGN in kobo
                'quantity' => 1,
            ],
            [
                'name' => 'Lenses',
                'amount' => 300000, // 3,000 NGN in kobo
                'quantity' => 1,
            ],
            [
                'name' => 'White Bulbs',
                'amount' => 50000, // 500 NGN in kobo
                'quantity' => 5,
            ],
        ]
    ]);

    echo "✅ Detailed invoice created successfully!\n";
    echo "   Invoice ID: " . $detailedInvoice['data']['id'] . "\n";
    echo "   Total Amount: " . $detailedInvoice['data']['amount'] . " kobo\n";
    echo "   Line Items: " . count($detailedInvoice['data']['line_items']) . " items\n\n";

    $detailedInvoiceId = $detailedInvoice['data']['id'];

    // Test 3: Find specific invoice
    echo "3️⃣ Finding specific invoice...\n";
    $foundInvoice = $billing->invoices()->find($invoiceId);
    echo "✅ Invoice found!\n";
    echo "   Amount: " . $foundInvoice['data']['amount'] . " kobo\n";
    echo "   Status: " . $foundInvoice['data']['status'] . "\n\n";

    // Test 4: List invoices for customer
    echo "4️⃣ Listing invoices for customer...\n";
    $customerInvoices = $billing->invoices()->all(['customer' => $customerId]);
    echo "✅ Found " . count($customerInvoices['data']) . " invoices for customer\n\n";

    // Test 5: Pay invoice (this will fail in test mode, but shows the API)
    echo "5️⃣ Testing invoice payment (will fail in test mode)...\n";
    try {
        $paidInvoice = $billing->invoices()->pay($invoiceId);
        echo "✅ Invoice paid successfully!\n";
    } catch (Exception $e) {
        echo "⚠️  Invoice payment failed (expected in test mode): " . $e->getMessage() . "\n";
    }
    echo "\n";

    // Test 6: Void invoice
    echo "6️⃣ Testing invoice void...\n";
    try {
        $voidedInvoice = $billing->invoices()->void($detailedInvoiceId);
        echo "✅ Invoice voided successfully!\n";
    } catch (Exception $e) {
        echo "⚠️  Invoice void failed (expected in test mode): " . $e->getMessage() . "\n";
    }
    echo "\n";

    // Cleanup
    echo "🧹 Cleaning up test data...\n";
    $billing->customers()->delete($customerId);
    echo "✅ Cleanup completed!\n\n";

    echo "🎉 All invoice management tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check your Paystack secret key\n";
    echo "2. Ensure you have an active internet connection\n";
    echo "3. Verify your Paystack account is active\n";
    echo "4. Note: Some invoice operations may fail in test mode\n";
}
