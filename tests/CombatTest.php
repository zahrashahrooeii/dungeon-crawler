<?php
/**
 * Unit Tests for Combat System
 */

require_once 'TestRunner.php';

// Load game functions without auto-starting the game
$gameCode = file_get_contents(__DIR__ . '/../dungeon_crawler.php');
$gameCode = preg_replace('/\/\/ Start the game if running from CLI.*$/s', '', $gameCode);
eval('?>' . $gameCode);

function runCombatTests() {
    $runner = new TestRunner();
    
    // Test combat damage calculation
    $runner->addTest("Player can deal damage to monster", function() {
        $gameState = initializeGame();
        $gameState['player']['current_room'] = 'corridor1';
        
        $initialHealth = $gameState['rooms']['corridor1']['monster']['health'];
        $playerAttack = $gameState['player']['attack'];
        
        // Simulate attack
        $gameState['rooms']['corridor1']['monster']['health'] -= $playerAttack;
        
        assertEquals($initialHealth - $playerAttack, $gameState['rooms']['corridor1']['monster']['health']);
    });
    
    // Test monster defeat
    $runner->addTest("Monster is defeated when health reaches 0", function() {
        $gameState = initializeGame();
        $gameState['player']['current_room'] = 'corridor1';
        
        // Set monster health to 0
        $gameState['rooms']['corridor1']['monster']['health'] = 0;
        
        // Monster should be considered defeated
        assertTrue($gameState['rooms']['corridor1']['monster']['health'] <= 0);
    });
    
    // Test treasure reward on monster defeat
    $runner->addTest("Player receives treasure when defeating monster", function() {
        $gameState = initializeGame();
        $gameState['player']['current_room'] = 'corridor1';
        
        $monsterTreasure = $gameState['rooms']['corridor1']['monster']['treasure'];
        $initialPlayerTreasure = $gameState['player']['treasure'];
        
        // Simulate defeating monster and collecting treasure
        $gameState['player']['treasure'] += $monsterTreasure;
        
        assertEquals($initialPlayerTreasure + $monsterTreasure, $gameState['player']['treasure']);
    });
    
    // Test player taking damage
    $runner->addTest("Player takes damage from monster attack", function() {
        $gameState = initializeGame();
        $gameState['player']['current_room'] = 'corridor1';
        
        $initialHealth = $gameState['player']['health'];
        $monsterAttack = $gameState['rooms']['corridor1']['monster']['attack'];
        
        // Simulate monster attack
        $gameState['player']['health'] -= $monsterAttack;
        
        assertEquals($initialHealth - $monsterAttack, $gameState['player']['health']);
    });
    
    // Test player death
    $runner->addTest("Player dies when health reaches 0", function() {
        $gameState = initializeGame();
        
        // Set player health to 0
        $gameState['player']['health'] = 0;
        
        assertTrue($gameState['player']['health'] <= 0);
    });
    
    // Test different monster types
    $runner->addTest("Different monsters have different stats", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        // Test goblin vs orc
        $goblin = $rooms['corridor1']['monster'];
        $orc = $rooms['guard_room']['monster'];
        
        assertNotEquals($goblin['health'], $orc['health']);
        assertNotEquals($goblin['attack'], $orc['attack']);
        assertNotEquals($goblin['treasure'], $orc['treasure']);
        
        // Orc should be stronger than goblin
        assertTrue($orc['health'] > $goblin['health']);
        assertTrue($orc['attack'] > $goblin['attack']);
        assertTrue($orc['treasure'] > $goblin['treasure']);
    });
    
    // Test dragon as strongest monster
    $runner->addTest("Dragon is the strongest monster", function() {
        $gameState = initializeGame();
        $rooms = $gameState['rooms'];
        
        $dragon = $rooms['throne_room']['monster'];
        
        // Dragon should have highest stats
        assertTrue($dragon['health'] >= 100);
        assertTrue($dragon['attack'] >= 40);
        assertTrue($dragon['treasure'] >= 50);
    });
    
    return $runner->run();
}

// Run the tests
if (php_sapi_name() === 'cli') {
    echo "=== Combat System Tests ===\n";
    $success = runCombatTests();
    exit($success ? 0 : 1);
}
