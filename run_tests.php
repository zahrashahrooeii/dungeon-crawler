<?php
/**
 * Test Runner for Dungeon Crawler
 * 
 * Runs all unit tests for the dungeon crawler game
 */

echo "=== DUNGEON CRAWLER TEST SUITE ===\n\n";

$testFiles = [
    'tests/GameLogicTest.php',
    'tests/CombatTest.php', 
    'tests/MovementTest.php'
];

$totalPassed = 0;
$totalFailed = 0;
$allTestsPassed = true;

foreach ($testFiles as $testFile) {
    if (file_exists($testFile)) {
        echo "Running tests from: $testFile\n";
        echo str_repeat("-", 50) . "\n";
        
        // Capture output from test file
        ob_start();
        $exitCode = 0;
        
        // Include the test file (it will run its tests)
        include $testFile;
        
        $output = ob_get_clean();
        echo $output;
        
        // Check if tests passed (exit code 0 means success)
        if ($exitCode !== 0) {
            $allTestsPassed = false;
        }
        
        echo "\n";
    } else {
        echo "Warning: Test file $testFile not found\n";
        $allTestsPassed = false;
    }
}

echo str_repeat("=", 60) . "\n";
echo "=== FINAL RESULTS ===\n";

if ($allTestsPassed) {
    echo "🎉 ALL TESTS PASSED! 🎉\n";
    echo "Your dungeon crawler is working correctly!\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED ❌\n";
    echo "Please check the test output above for details.\n";
    exit(1);
}
