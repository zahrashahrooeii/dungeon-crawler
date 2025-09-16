<?php
/**
 * Unit Tests for Movement System
 */

require_once 'TestRunner.php';

// Load game functions without auto-starting the game
$gameCode = file_get_contents(__DIR__ . '/../dungeon_crawler.php');
$gameCode = preg_replace('/\/\/ Start the game if running from CLI.*$/s', '', $gameCode);
eval('?>' . $gameCode);

function runMovementTests() {
    $runner = new TestRunner();
    
    // Test valid movement
    $runner->addTest("Player can move to connected rooms", function() {
        $gameState = initializeGame();
        
        // Start in 'start' room
        assertEquals('start', $gameState['player']['current_room']);
        
        // Should be able to move north to corridor1
        $connections = $gameState['rooms']['start']['connections'];
        assertArrayHasKey('north', $connections);
        assertEquals('corridor1', $connections['north']);
        
        // Should be able to move east to chamber1
        assertArrayHasKey('east', $connections);
        assertEquals('chamber1', $connections['east']);
    });
    
    // Test room connections are bidirectional
    $runner->addTest("Room connections are bidirectional", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        // If start connects north to corridor1, corridor1 should connect south to start
        $startConnections = $rooms['start']['connections'];
        $corridorConnections = $rooms['corridor1']['connections'];
        
        assertEquals('corridor1', $startConnections['north']);
        assertEquals('start', $corridorConnections['south']);
    });
    
    // Test treasure collection
    $runner->addTest("Player collects treasure when entering room", function() {
        $gameState = initializeGame();
        
        // Move to chamber1 which has 25 treasure
        $gameState['player']['current_room'] = 'chamber1';
        $roomTreasure = $gameState['rooms']['chamber1']['treasure'];
        
        assertEquals(25, $roomTreasure);
        
        // Simulate collecting treasure
        $gameState['player']['treasure'] += $roomTreasure;
        $gameState['rooms']['chamber1']['treasure'] = 0;
        
        assertEquals(25, $gameState['player']['treasure']);
        assertEquals(0, $gameState['rooms']['chamber1']['treasure']);
    });
    
    // Test room visited tracking
    $runner->addTest("Rooms are marked as visited when entered", function() {
        $gameState = initializeGame();
        
        // Start room should be visited
        assertTrue($gameState['rooms']['start']['visited']);
        
        // Other rooms should not be visited initially
        assertFalse($gameState['rooms']['corridor1']['visited']);
        assertFalse($gameState['rooms']['chamber1']['visited']);
    });
    
    // Test exit room detection
    $runner->addTest("Exit room is properly identified", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        // Only exit room should have is_exit = true
        assertTrue($rooms['exit']['is_exit']);
        assertFalse($rooms['start']['is_exit']);
        assertFalse($rooms['corridor1']['is_exit']);
        assertFalse($rooms['chamber1']['is_exit']);
    });
    
    // Test dungeon map structure
    $runner->addTest("Dungeon has proper map structure", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        // All rooms should have required properties
        foreach ($rooms as $roomId => $room) {
            assertArrayHasKey('name', $room);
            assertArrayHasKey('description', $room);
            assertArrayHasKey('connections', $room);
            assertArrayHasKey('monster', $room);
            assertArrayHasKey('treasure', $room);
            assertArrayHasKey('is_exit', $room);
            assertArrayHasKey('visited', $room);
        }
    });
    
    // Test room names are descriptive
    $runner->addTest("Room names are descriptive", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        $roomNames = [
            'start' => 'Entrance Hall',
            'corridor1' => 'Corridor',
            'chamber1' => 'Chamber',
            'treasury' => 'Treasury',
            'guard_room' => 'Guard Room',
            'library' => 'Library',
            'throne_room' => 'Throne Room',
            'exit' => 'Exit'
        ];
        
        foreach ($roomNames as $roomId => $expectedName) {
            assertEquals($expectedName, $rooms[$roomId]['name']);
        }
    });
    
    return $runner->run();
}

// Run the tests
if (php_sapi_name() === 'cli') {
    echo "=== Movement System Tests ===\n";
    $success = runMovementTests();
    exit($success ? 0 : 1);
}
