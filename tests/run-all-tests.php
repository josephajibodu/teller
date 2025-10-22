<?php

echo "🚀 Running All Teller Tests\n";
echo "==========================\n\n";

$tests = [
    'test-plans.php' => '📋 Plan Management',
    'test-customers.php' => '👤 Customer Management',
    'test-subscriptions.php' => '💳 Subscription Management',
    'test-invoices.php' => '🧾 Invoice Management',
    'test-money-helper.php' => '💰 Money Helper',
    'test-date-helper.php' => '📅 Date Helper',
    'test-fluent-api.php' => '🔗 Fluent API',
];

$results = [];
$totalTests = count($tests);
$passedTests = 0;

foreach ($tests as $testFile => $testName) {
    echo "Running $testName...\n";
    echo str_repeat('-', 50)."\n";

    $startTime = microtime(true);

    // Capture output
    ob_start();
    $exitCode = 0;

    try {
        include __DIR__.'/'.$testFile;
    } catch (Exception $e) {
        echo '❌ Test failed with exception: '.$e->getMessage()."\n";
        $exitCode = 1;
    }

    $output = ob_get_clean();
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime) * 1000, 2);

    // Check if test passed (look for success indicators)
    $passed = strpos($output, '🎉') !== false || strpos($output, '✅') !== false;

    if ($passed) {
        $passedTests++;
        $status = '✅ PASSED';
    } else {
        $status = '❌ FAILED';
    }

    $results[$testFile] = [
        'name' => $testName,
        'status' => $status,
        'duration' => $duration.'ms',
        'output' => $output,
    ];

    echo $output;
    echo "\n".str_repeat('=', 50)."\n\n";
}

// Summary
echo "📊 Test Summary\n";
echo "==============\n";
echo "Total Tests: $totalTests\n";
echo "Passed: $passedTests\n";
echo 'Failed: '.($totalTests - $passedTests)."\n";
echo 'Success Rate: '.round(($passedTests / $totalTests) * 100, 1)."%\n\n";

echo "📋 Detailed Results:\n";
echo "===================\n";
foreach ($results as $testFile => $result) {
    echo $result['status'].' - '.$result['name'].' ('.$result['duration'].")\n";
}

if ($passedTests === $totalTests) {
    echo "\n🎉 All tests passed! Teller package is working perfectly!\n";
} else {
    echo "\n⚠️  Some tests failed. Please check the output above for details.\n";
}
