
<?php
/**
 * Simple Test Runner for Dungeon Crawler
 * 
 * A lightweight testing framework that doesn't require external dependencies
 */

class TestRunner {
    private $tests = [];
    private $passed = 0;
    private $failed = 0;
    
    public function addTest($name, $callback) {
        $this->tests[] = ['name' => $name, 'callback' => $callback];
    }
    
    public function run() {
        echo "Running " . count($this->tests) . " tests...\n\n";
        
        foreach ($this->tests as $test) {
            try {
                $test['callback']();
                echo "✓ " . $test['name'] . "\n";
                $this->passed++;
            } catch (Exception $e) {
                echo "✗ " . $test['name'] . " - " . $e->getMessage() . "\n";
                $this->failed++;
            }
        }
        
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "Results: {$this->passed} passed, {$this->failed} failed\n";
        
        return $this->failed === 0;
    }
}

// Test assertion functions
function assertTrue($condition, $message = "Expected true") {
    if (!$condition) {
        throw new Exception($message);
    }
}

function assertFalse($condition, $message = "Expected false") {
    if ($condition) {
        throw new Exception($message);
    }
}

function assertEquals($expected, $actual, $message = null) {
    if ($expected !== $actual) {
        $msg = $message ?: "Expected '$expected', got '$actual'";
        throw new Exception($msg);
    }
}

function assertNotEquals($expected, $actual, $message = null) {
    if ($expected === $actual) {
        $msg = $message ?: "Expected not '$expected', got '$actual'";
        throw new Exception($msg);
    }
}

function assertArrayHasKey($key, $array, $message = null) {
    if (!array_key_exists($key, $array)) {
        $msg = $message ?: "Array does not have key '$key'";
        throw new Exception($msg);
    }
}

function assertNotNull($value, $message = "Expected not null") {
    if ($value === null) {
        throw new Exception($message);
    }
}

function assertNull($value, $message = "Expected null") {
    if ($value !== null) {
        throw new Exception($message);
    }
}
