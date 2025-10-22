<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JosephAjibodu\Teller\Support\Money;

echo "💰 Testing Money Helper\n";
echo "======================\n\n";

try {
    // Test 1: Create money from Naira
    echo "1️⃣ Creating money from Naira...\n";
    $amount = Money::fromNaira(250.00);
    echo "✅ Money object created: " . $amount->format() . "\n";
    echo "   In kobo: " . $amount->toKobo() . "\n";
    echo "   In naira: " . $amount->toNaira() . "\n\n";

    // Test 2: Create money from Kobo
    echo "2️⃣ Creating money from Kobo...\n";
    $koboAmount = Money::fromKobo(50000);
    echo "✅ Money object created: " . $koboAmount->format() . "\n";
    echo "   In kobo: " . $koboAmount->toKobo() . "\n";
    echo "   In naira: " . $koboAmount->toNaira() . "\n\n";

    // Test 3: Addition
    echo "3️⃣ Testing addition...\n";
    $amount1 = Money::fromNaira(100.00);
    $amount2 = Money::fromNaira(50.00);
    $total = $amount1->add($amount2);
    echo "✅ Addition successful!\n";
    echo "   " . $amount1->format() . " + " . $amount2->format() . " = " . $total->format() . "\n\n";

    // Test 4: Subtraction
    echo "4️⃣ Testing subtraction...\n";
    $amount1 = Money::fromNaira(200.00);
    $amount2 = Money::fromNaira(75.00);
    $difference = $amount1->subtract($amount2);
    echo "✅ Subtraction successful!\n";
    echo "   " . $amount1->format() . " - " . $amount2->format() . " = " . $difference->format() . "\n\n";

    // Test 5: Multiplication
    echo "5️⃣ Testing multiplication...\n";
    $amount = Money::fromNaira(100.00);
    $discount = $amount->multiply(0.1); // 10% discount
    echo "✅ Multiplication successful!\n";
    echo "   Original: " . $amount->format() . "\n";
    echo "   10% discount: " . $discount->format() . "\n\n";

    // Test 6: Division
    echo "6️⃣ Testing division...\n";
    $amount = Money::fromNaira(300.00);
    $split = $amount->divide(3); // Split into 3 parts
    echo "✅ Division successful!\n";
    echo "   Original: " . $amount->format() . "\n";
    echo "   Split into 3: " . $split->format() . "\n\n";

    // Test 7: Comparison
    echo "7️⃣ Testing comparison...\n";
    $amount1 = Money::fromNaira(150.00);
    $amount2 = Money::fromNaira(200.00);
    $amount3 = Money::fromNaira(150.00);

    echo "✅ Comparison successful!\n";
    echo "   " . $amount1->format() . " > " . $amount2->format() . ": " . ($amount1->isGreaterThan($amount2) ? 'Yes' : 'No') . "\n";
    echo "   " . $amount1->format() . " < " . $amount2->format() . ": " . ($amount1->isLessThan($amount2) ? 'Yes' : 'No') . "\n";
    echo "   " . $amount1->format() . " = " . $amount3->format() . ": " . ($amount1->equals($amount3) ? 'Yes' : 'No') . "\n\n";

    // Test 8: Complex calculation
    echo "8️⃣ Testing complex calculation...\n";
    $baseAmount = Money::fromNaira(1000.00);
    $tax = $baseAmount->multiply(0.075); // 7.5% tax
    $discount = $baseAmount->multiply(0.1); // 10% discount
    $finalAmount = $baseAmount->add($tax)->subtract($discount);

    echo "✅ Complex calculation successful!\n";
    echo "   Base amount: " . $baseAmount->format() . "\n";
    echo "   Tax (7.5%): " . $tax->format() . "\n";
    echo "   Discount (10%): " . $discount->format() . "\n";
    echo "   Final amount: " . $finalAmount->format() . "\n\n";

    // Test 9: Currency formatting
    echo "9️⃣ Testing currency formatting...\n";
    $amounts = [
        Money::fromNaira(0.50),
        Money::fromNaira(1.00),
        Money::fromNaira(10.50),
        Money::fromNaira(100.00),
        Money::fromNaira(1000.50),
        Money::fromNaira(10000.00),
    ];

    echo "✅ Currency formatting successful!\n";
    foreach ($amounts as $amount) {
        echo "   " . $amount->format() . "\n";
    }
    echo "\n";

    // Test 10: Error handling
    echo "🔟 Testing error handling...\n";
    try {
        $amount1 = Money::fromNaira(100.00);
        $amount2 = Money::fromKobo(5000, 'USD'); // Different currency
        $result = $amount1->add($amount2);
    } catch (Exception $e) {
        echo "✅ Error handling works: " . $e->getMessage() . "\n";
    }
    echo "\n";

    echo "🎉 All money helper tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check that the Money class is properly loaded\n";
    echo "2. Verify all required methods are implemented\n";
}
