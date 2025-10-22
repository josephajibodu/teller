<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JosephAjibodu\Teller\Support\DateHelper;

echo "📅 Testing Date Helper\n";
echo "=====================\n\n";

try {
    $date = new \DateTime();

    // Test 1: Days in month
    echo "1️⃣ Testing days in month...\n";
    $daysInMonth = DateHelper::daysInMonth($date);
    echo "✅ Days in current month: " . $daysInMonth . "\n\n";

    // Test 2: Days remaining in month
    echo "2️⃣ Testing days remaining in month...\n";
    $daysRemaining = DateHelper::daysRemainingInMonth($date);
    echo "✅ Days remaining in current month: " . $daysRemaining . "\n\n";

    // Test 3: Days elapsed in month
    echo "3️⃣ Testing days elapsed in month...\n";
    $daysElapsed = DateHelper::daysElapsedInMonth($date);
    echo "✅ Days elapsed in current month: " . $daysElapsed . "\n\n";

    // Test 4: Next billing date
    echo "4️⃣ Testing next billing date...\n";
    $nextMonthly = DateHelper::nextBillingDate($date, 'monthly');
    $nextYearly = DateHelper::nextBillingDate($date, 'yearly');
    $nextWeekly = DateHelper::nextBillingDate($date, 'weekly');
    $nextDaily = DateHelper::nextBillingDate($date, 'daily');

    echo "✅ Next billing dates calculated:\n";
    echo "   Monthly: " . $nextMonthly->format('Y-m-d') . "\n";
    echo "   Yearly: " . $nextYearly->format('Y-m-d') . "\n";
    echo "   Weekly: " . $nextWeekly->format('Y-m-d') . "\n";
    echo "   Daily: " . $nextDaily->format('Y-m-d') . "\n\n";

    // Test 5: Date comparison
    echo "5️⃣ Testing date comparison...\n";
    $date1 = new \DateTime('2024-01-15');
    $date2 = new \DateTime('2024-01-15');
    $date3 = new \DateTime('2024-01-16');
    $date4 = new \DateTime('2024-02-15');

    echo "✅ Date comparison results:\n";
    echo "   Same day (2024-01-15 vs 2024-01-15): " . (DateHelper::isSameDay($date1, $date2) ? 'Yes' : 'No') . "\n";
    echo "   Same day (2024-01-15 vs 2024-01-16): " . (DateHelper::isSameDay($date1, $date3) ? 'Yes' : 'No') . "\n";
    echo "   Same month (2024-01-15 vs 2024-02-15): " . (DateHelper::isSameMonth($date1, $date4) ? 'Yes' : 'No') . "\n\n";

    // Test 6: Start and end of day
    echo "6️⃣ Testing start and end of day...\n";
    $startOfDay = DateHelper::startOfDay($date);
    $endOfDay = DateHelper::endOfDay($date);

    echo "✅ Start and end of day:\n";
    echo "   Start of day: " . $startOfDay->format('Y-m-d H:i:s') . "\n";
    echo "   End of day: " . $endOfDay->format('Y-m-d H:i:s') . "\n\n";

    // Test 7: Start and end of month
    echo "7️⃣ Testing start and end of month...\n";
    $startOfMonth = DateHelper::startOfMonth($date);
    $endOfMonth = DateHelper::endOfMonth($date);

    echo "✅ Start and end of month:\n";
    echo "   Start of month: " . $startOfMonth->format('Y-m-d H:i:s') . "\n";
    echo "   End of month: " . $endOfMonth->format('Y-m-d H:i:s') . "\n\n";

    // Test 8: Billing cycle calculations
    echo "8️⃣ Testing billing cycle calculations...\n";
    $subscriptionStart = new \DateTime('2024-01-15');
    $currentDate = new \DateTime('2024-01-25');

    $daysInCycle = DateHelper::daysInMonth($subscriptionStart);
    $daysElapsed = DateHelper::daysElapsedInMonth($currentDate);
    $daysRemaining = DateHelper::daysRemainingInMonth($currentDate);
    $nextBilling = DateHelper::nextBillingDate($currentDate, 'monthly');

    echo "✅ Billing cycle calculations:\n";
    echo "   Subscription started: " . $subscriptionStart->format('Y-m-d') . "\n";
    echo "   Current date: " . $currentDate->format('Y-m-d') . "\n";
    echo "   Days in cycle: " . $daysInCycle . "\n";
    echo "   Days elapsed: " . $daysElapsed . "\n";
    echo "   Days remaining: " . $daysRemaining . "\n";
    echo "   Next billing: " . $nextBilling->format('Y-m-d') . "\n\n";

    // Test 9: Proration calculations
    echo "9️⃣ Testing proration calculations...\n";
    $planStartDate = new \DateTime('2024-01-01');
    $upgradeDate = new \DateTime('2024-01-15');
    $monthEnd = DateHelper::endOfMonth($upgradeDate);

    $totalDaysInMonth = DateHelper::daysInMonth($upgradeDate);
    $daysRemaining = DateHelper::daysRemainingInMonth($upgradeDate);
    $prorationRatio = $daysRemaining / $totalDaysInMonth;

    echo "✅ Proration calculations:\n";
    echo "   Plan start: " . $planStartDate->format('Y-m-d') . "\n";
    echo "   Upgrade date: " . $upgradeDate->format('Y-m-d') . "\n";
    echo "   Days remaining: " . $daysRemaining . "\n";
    echo "   Total days in month: " . $totalDaysInMonth . "\n";
    echo "   Proration ratio: " . round($prorationRatio * 100, 2) . "%\n\n";

    // Test 10: Edge cases
    echo "🔟 Testing edge cases...\n";
    $leapYear = new \DateTime('2024-02-29'); // Leap year
    $nonLeapYear = new \DateTime('2023-02-28'); // Non-leap year

    echo "✅ Edge cases:\n";
    echo "   Leap year February days: " . DateHelper::daysInMonth($leapYear) . "\n";
    echo "   Non-leap year February days: " . DateHelper::daysInMonth($nonLeapYear) . "\n";
    echo "   Leap year days remaining: " . DateHelper::daysRemainingInMonth($leapYear) . "\n";
    echo "   Non-leap year days remaining: " . DateHelper::daysRemainingInMonth($nonLeapYear) . "\n\n";

    echo "🎉 All date helper tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Debugging Tips:\n";
    echo "1. Check that the DateHelper class is properly loaded\n";
    echo "2. Verify all required methods are implemented\n";
    echo "3. Ensure DateTime objects are being created correctly\n";
}
