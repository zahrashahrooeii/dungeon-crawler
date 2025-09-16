<?php
/**
 * Unit Tests for Game Logic Functions
 */

require_once 'TestRunner.php';

// Load game functions without auto-starting the game
$gameCode = file_get_contents(__DIR__ . '/../dungeon_crawler.php');
$gameCode = preg_replace('/\/\/ Start the game if running from CLI.*$/s', '', $gameCode);
eval('?>' . $gameCode);

// We need to extract the game logic functions for testing
// Since they're in the main file, we'll test them by calling the main functions

function runGameLogicTests() {
    $runner = new TestRunner();
    
    // Test game initialization
    $runner->addTest("Game initialization creates valid state", function() {
        $gameState = initializeGame();
        
        assertArrayHasKey('player', $gameState);
        assertArrayHasKey('rooms', $gameState);
        assertEquals(100, $gameState['player']['health']);
        assertEquals(100, $gameState['player']['max_health']);
        assertEquals(20, $gameState['player']['attack']);
        assertEquals(0, $gameState['player']['treasure']);
        assertEquals('start', $gameState['player']['current_room']);
    });
    
    // Test room structure
    $runner->addTest("Dungeon has all required rooms", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        $requiredRooms = ['start', 'corridor1', 'chamber1', 'treasury', 'guard_room', 'library', 'throne_room', 'storage', 'armory', 'kitchen', 'training_room', 'exit'];
        foreach ($requiredRooms as $roomId) {
            assertArrayHasKey($roomId, $rooms, "Missing room: $roomId");
        }
    });
    
    // Test room connections
    $runner->addTest("Rooms have proper connections", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        // Test start room connections (now has all 4 directions)
        assertArrayHasKey('connections', $rooms['start']);
        assertArrayHasKey('north', $rooms['start']['connections']);
        assertArrayHasKey('east', $rooms['start']['connections']);
        assertArrayHasKey('south', $rooms['start']['connections']);
        assertArrayHasKey('west', $rooms['start']['connections']);
        assertEquals('corridor1', $rooms['start']['connections']['north']);
        assertEquals('chamber1', $rooms['start']['connections']['east']);
        assertEquals('storage', $rooms['start']['connections']['south']);
        assertEquals('armory', $rooms['start']['connections']['west']);
    });
    
    // Test monster data
    $runner->addTest("Monsters have correct stats", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        // Test goblin in corridor1
        $goblin = $rooms['corridor1']['monster'];
        assertNotNull($goblin);
        assertEquals('Goblin', $goblin['name']);
        assertEquals(30, $goblin['health']);
        assertEquals(30, $goblin['max_health']);
        assertEquals(15, $goblin['attack']);
        assertEquals(10, $goblin['treasure']);
    });
    
    // Test treasure in rooms
    $runner->addTest("Rooms have correct treasure amounts", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        assertEquals(0, $rooms['start']['treasure']);
        assertEquals(25, $rooms['chamber1']['treasure']);
        assertEquals(50, $rooms['treasury']['treasure']);
        assertEquals(75, $rooms['throne_room']['treasure']);
    });
    
    // Test exit room
    $runner->addTest("Exit room is properly configured", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        assertTrue($rooms['exit']['is_exit']);
        assertNull($rooms['exit']['monster']);
        assertEquals(0, $rooms['exit']['treasure']);
    });
    
    // Test room visited status
    $runner->addTest("Start room is marked as visited", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        assertTrue($rooms['start']['visited']);
        assertFalse($rooms['corridor1']['visited']);
    });
    
    return $runner->run();
}

// Run the tests
if (php_sapi_name() === 'cli') {
    echo "=== Game Logic Tests ===\n";
    $success = runGameLogicTests();
    exit($success ? 0 : 1);
}
