<?php

echo "🚀 Running All Teller Examples\n";
echo "=============================\n\n";

$examples = [
    'plan-management.php' => '📋 Plan Management',
    'customer-management.php' => '👤 Customer Management',
    'subscription-management.php' => '💳 Subscription Management',
    'invoice-management.php' => '🧾 Invoice Management',
    'money-helper.php' => '💰 Money Helper',
    'date-helper.php' => '📅 Date Helper',
    'fluent-api.php' => '🔗 Fluent API',
];

$results = [];
$totalExamples = count($examples);
$completedExamples = 0;

foreach ($examples as $exampleFile => $exampleName) {
    echo "Running $exampleName...\n";
    echo str_repeat('-', 50)."\n";

    $startTime = microtime(true);

    // Capture output
    ob_start();
    $exitCode = 0;

    try {
        include __DIR__.'/'.$exampleFile;
    } catch (Exception $e) {
        echo '❌ Example failed with exception: '.$e->getMessage()."\n";
        $exitCode = 1;
    }

    $output = ob_get_clean();
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime) * 1000, 2);

    // Check if example completed successfully
    $completed = strpos($output, '🎉') !== false || strpos($output, '✅') !== false;

    if ($completed) {
        $completedExamples++;
        $status = '✅ COMPLETED';
    } else {
        $status = '❌ FAILED';
    }

    $results[$exampleFile] = [
        'name' => $exampleName,
        'status' => $status,
        'duration' => $duration.'ms',
        'output' => $output,
    ];

    echo $output;
    echo "\n".str_repeat('=', 50)."\n\n";
}

// Summary
echo "📊 Example Summary\n";
echo "=================\n";
echo "Total Examples: $totalExamples\n";
echo "Completed: $completedExamples\n";
echo 'Failed: '.($totalExamples - $completedExamples)."\n";
echo 'Success Rate: '.round(($completedExamples / $totalExamples) * 100, 1)."%\n\n";

echo "📋 Detailed Results:\n";
echo "===================\n";
foreach ($results as $exampleFile => $result) {
    echo $result['status'].' - '.$result['name'].' ('.$result['duration'].")\n";
}

if ($completedExamples === $totalExamples) {
    echo "\n🎉 All examples completed successfully! Teller package is working perfectly!\n";
} else {
    echo "\n⚠️  Some examples failed. Please check the output above for details.\n";
}
