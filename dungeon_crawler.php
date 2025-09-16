<?php
/**
 * Simple Dungeon Crawler Game
 * 
 * A text-based dungeon crawler
 */

// ============================================================================
// GAME LOGIC (Business Rules)
// ============================================================================

/**
 * Initialize the game state
 */
function initializeGame() {
    return [
        'player' => [
            'health' => 100,
            'max_health' => 100,
            'attack' => 20,
            'treasure' => 0,
            'current_room' => 'start'
        ],
        'rooms' => [
            'start' => [
                'name' => 'Entrance Hall',
                'description' => 'A dark, damp entrance hall with torches flickering on the walls.',
                'connections' => ['north' => 'corridor1', 'east' => 'chamber1', 'south' => 'storage', 'west' => 'armory'],
                'monster' => null,
                'treasure' => 0,
                'is_exit' => false,
                'visited' => true
            ],
            'corridor1' => [
                'name' => 'Corridor',
                'description' => 'A narrow stone corridor with mysterious markings on the walls.',
                'connections' => ['south' => 'start', 'north' => 'guard_room', 'east' => 'treasury', 'west' => 'training_room'],
                'monster' => ['name' => 'Goblin', 'health' => 30, 'max_health' => 30, 'attack' => 15, 'treasure' => 10],
                'treasure' => 0,
                'is_exit' => false,
                'visited' => false
            ],
            'chamber1' => [
                'name' => 'Chamber',
                'description' => 'A large chamber with ancient furniture covered in dust.',
                'connections' => ['west' => 'start', 'north' => 'treasury', 'east' => 'library', 'south' => 'kitchen'],
                'monster' => null,
                'treasure' => 25,
                'is_exit' => false,
                'visited' => false
            ],
            'treasury' => [
                'name' => 'Treasury',
                'description' => 'A room filled with gold and precious gems.',
                'connections' => ['west' => 'corridor1', 'south' => 'chamber1', 'north' => 'throne_room'],
                'monster' => null,
                'treasure' => 50,
                'is_exit' => false,
                'visited' => false
            ],
            'guard_room' => [
                'name' => 'Guard Room',
                'description' => 'A room that once housed guards, now occupied by creatures.',
                'connections' => ['south' => 'corridor1', 'east' => 'throne_room'],
                'monster' => ['name' => 'Orc', 'health' => 50, 'max_health' => 50, 'attack' => 25, 'treasure' => 20],
                'treasure' => 0,
                'is_exit' => false,
                'visited' => false
            ],
            'library' => [
                'name' => 'Library',
                'description' => 'An old library with books scattered everywhere.',
                'connections' => ['west' => 'chamber1', 'north' => 'exit'],
                'monster' => ['name' => 'Troll', 'health' => 80, 'max_health' => 80, 'attack' => 35, 'treasure' => 30],
                'treasure' => 0,
                'is_exit' => false,
                'visited' => false
            ],
            'throne_room' => [
                'name' => 'Throne Room',
                'description' => 'A grand throne room with ornate decorations.',
                'connections' => ['south' => 'treasury', 'west' => 'guard_room', 'east' => 'exit'],
                'monster' => ['name' => 'Dragon', 'health' => 120, 'max_health' => 120, 'attack' => 50, 'treasure' => 100],
                'treasure' => 75,
                'is_exit' => false,
                'visited' => false
            ],
            'storage' => [
                'name' => 'Storage Room',
                'description' => 'A dusty storage room filled with old crates and barrels.',
                'connections' => ['north' => 'start', 'east' => 'kitchen'],
                'monster' => null,
                'treasure' => 15,
                'is_exit' => false,
                'visited' => false
            ],
            'armory' => [
                'name' => 'Armory',
                'description' => 'An old armory with rusted weapons and armor.',
                'connections' => ['east' => 'start', 'north' => 'training_room'],
                'monster' => ['name' => 'Skeleton', 'health' => 40, 'max_health' => 40, 'attack' => 20, 'treasure' => 15],
                'treasure' => 0,
                'is_exit' => false,
                'visited' => false
            ],
            'kitchen' => [
                'name' => 'Kitchen',
                'description' => 'A large kitchen with a massive stone oven.',
                'connections' => ['west' => 'storage', 'north' => 'chamber1'],
                'monster' => null,
                'treasure' => 20,
                'is_exit' => false,
                'visited' => false
            ],
            'training_room' => [
                'name' => 'Training Room',
                'description' => 'A room with training dummies and practice weapons.',
                'connections' => ['south' => 'armory', 'east' => 'corridor1'],
                'monster' => null,
                'treasure' => 10,
                'is_exit' => false,
                'visited' => false
            ],
            'exit' => [
                'name' => 'Exit',
                'description' => 'A bright passage leading to freedom!',
                'connections' => ['south' => 'library', 'west' => 'throne_room'],
                'monster' => null,
                'treasure' => 0,
                'is_exit' => true,
                'visited' => false
            ]
        ],
        'game_over' => false,
        'victory' => false
    ];
}

/**
 * Get current room information
 */
function getCurrentRoom($gameState) {
    return $gameState['rooms'][$gameState['player']['current_room']];
}

/**
 * Check if player can move in a direction
 */
function canMove($gameState, $direction) {
    $currentRoom = getCurrentRoom($gameState);
    return isset($currentRoom['connections'][$direction]);
}

/**
 * Move player to a new room
 */
function movePlayer($gameState, $direction) {
    $currentRoom = getCurrentRoom($gameState);
    
    if (!canMove($gameState, $direction)) {
        return ['success' => false, 'message' => "You can't go that way!"];
    }
    
    if ($currentRoom['monster'] !== null) {
        return ['success' => false, 'message' => "You can't leave while a monster is in the room!"];
    }
    
    $newRoomId = $currentRoom['connections'][$direction];
    $gameState['player']['current_room'] = $newRoomId;
    $gameState['rooms'][$newRoomId]['visited'] = true;
    
    $newRoom = $gameState['rooms'][$newRoomId];
    $message = "You move to the {$newRoom['name']}.";
    
    // Check for treasure
    if ($newRoom['treasure'] > 0) {
        $gameState['player']['treasure'] += $newRoom['treasure'];
        $message .= " You found {$newRoom['treasure']} gold coins!";
        $gameState['rooms'][$newRoomId]['treasure'] = 0; // Remove treasure
    }
    
    // Check victory condition
    if ($newRoom['is_exit'] && $newRoom['monster'] === null) {
        $gameState['victory'] = true;
        $gameState['game_over'] = true;
    }
    
    return ['success' => true, 'message' => $message, 'gameState' => $gameState];
}

/**
 * Attack monster in current room
 */
function attackMonster($gameState) {
    $currentRoom = getCurrentRoom($gameState);
    
    if ($currentRoom['monster'] === null) {
        return ['success' => false, 'message' => "There's nothing to attack here!"];
    }
    
    $monster = $currentRoom['monster'];
    $playerAttack = $gameState['player']['attack'];
    
    // Player attacks first
    $monster['health'] -= $playerAttack;
    $message = "You attack the {$monster['name']} for {$playerAttack} damage! ";
    
    if ($monster['health'] <= 0) {
        // Monster defeated
        $treasureReward = $monster['treasure'];
        $gameState['player']['treasure'] += $treasureReward;
        $gameState['rooms'][$gameState['player']['current_room']]['monster'] = null;
        $message .= "The {$monster['name']} is defeated! ";
        if ($treasureReward > 0) {
            $message .= "You found {$treasureReward} gold coins on the monster!";
        }
    } else {
        // Monster attacks back
        $monsterAttack = $monster['attack'];
        $gameState['player']['health'] -= $monsterAttack;
        $gameState['rooms'][$gameState['player']['current_room']]['monster'] = $monster;
        
        $message .= "The {$monster['name']} attacks you for {$monsterAttack} damage! ";
        
        if ($gameState['player']['health'] <= 0) {
            $gameState['player']['health'] = 0;
            $gameState['game_over'] = true;
            $message .= "You have been defeated!";
        } else {
            $message .= "You have {$gameState['player']['health']} health remaining.";
        }
    }
    
    return ['success' => true, 'message' => $message, 'gameState' => $gameState];
}

/**
 * Get dungeon map
 */
function getDungeonMap($gameState) {
    $map = "Dungeon Map:\n";
    $map .= "============\n\n";
    
    foreach ($gameState['rooms'] as $roomId => $room) {
        $visited = $room['visited'] ? "✓" : "?";
        $monster = $room['monster'] ? "👹" : "";
        $treasure = $room['treasure'] > 0 ? "💰" : "";
        $exit = $room['is_exit'] ? "🚪" : "";
        
        $map .= sprintf("%-12s %s %s%s%s - %s\n", 
            $roomId, $visited, $monster, $treasure, $exit, $room['name']);
    }
    
    return $map;
}

// ============================================================================
// I/O FUNCTIONS (User Interface)
// ============================================================================

/**
 * Display current room information
 */
function displayCurrentRoom($gameState) {
    $room = getCurrentRoom($gameState);
    
    echo "\n=== " . $room['name'] . " ===\n";
    echo $room['description'] . "\n\n";
    
    if ($room['monster'] !== null) {
        $monster = $room['monster'];
        echo "A " . $monster['name'] . " blocks your path! ";
        echo "(Health: " . $monster['health'] . "/" . $monster['max_health'] . ")\n";
    }
    
    if ($room['treasure'] > 0) {
        echo "You see " . $room['treasure'] . " gold coins glittering in the corner.\n";
    }
    
    if ($room['is_exit']) {
        echo "You see a bright light ahead - this is the exit!\n";
    }
    
    $directions = array_keys($room['connections']);
    if (!empty($directions)) {
        echo "\nYou can go: " . implode(", ", $directions) . "\n";
    }
}

/**
 * Display player status
 */
function displayPlayerStatus($gameState) {
    $player = $gameState['player'];
    echo "\n--- Player Status ---\n";
    echo "Health: {$player['health']}/{$player['max_health']}\n";
    echo "Attack: {$player['attack']}\n";
    echo "Treasure: {$player['treasure']} gold\n";
    echo "-------------------\n";
}

/**
 * Get user input
 */
function getUserInput() {
    echo "\nWhat do you want to do? (north/south/east/west/attack/status/map/quit): ";
    return trim(fgets(STDIN));
}

/**
 * Process user command
 */
function processCommand($command, $gameState) {
    $command = strtolower($command);
    
    switch ($command) {
        case 'north':
        case 'n':
            return movePlayer($gameState, 'north');
        case 'south':
        case 's':
            return movePlayer($gameState, 'south');
        case 'east':
        case 'e':
            return movePlayer($gameState, 'east');
        case 'west':
        case 'w':
            return movePlayer($gameState, 'west');
        case 'attack':
        case 'a':
            return attackMonster($gameState);
        case 'status':
            displayPlayerStatus($gameState);
            return ['success' => true, 'message' => '', 'gameState' => $gameState];
        case 'map':
            echo "\n" . getDungeonMap($gameState) . "\n";
            return ['success' => true, 'message' => '', 'gameState' => $gameState];
        case 'quit':
        case 'q':
            $gameState['game_over'] = true;
            return ['success' => true, 'message' => 'Thanks for playing!', 'gameState' => $gameState];
        default:
            return ['success' => false, 'message' => 'Invalid command. Try: north, south, east, west, attack, status, map, or quit.'];
    }
}

/**
 * Display game over screen
 */
function displayGameOver($gameState) {
    echo "\n=== GAME OVER ===\n";
    
    if ($gameState['victory']) {
        echo "Congratulations! You have escaped the dungeon!\n";
        $player = $gameState['player'];
        echo "Final Score:\n";
        echo "- Health: {$player['health']}/{$player['max_health']}\n";
        echo "- Treasure Collected: {$player['treasure']} gold\n";
        echo "You are a true hero!\n";
    } else {
        echo "Your adventure has ended. Better luck next time!\n";
    }
    
    echo "================\n";
}

// ============================================================================
// MAIN GAME LOOP
// ============================================================================

/**
 * Main game function
 */
function startGame() {
    echo "=== DUNGEON CRAWLER ===\n";
    echo "Welcome to the dungeon, brave adventurer!\n";
    echo "Navigate through rooms, fight monsters, collect treasure, and find the exit!\n\n";
    
    $gameState = initializeGame();
    displayCurrentRoom($gameState);
    
    while (!$gameState['game_over']) {
        displayPlayerStatus($gameState);
        $command = getUserInput();
        $result = processCommand($command, $gameState);
        
        if ($result['success']) {
            $gameState = $result['gameState'];
            if ($result['message']) {
                echo $result['message'] . "\n";
            }
            if (!$gameState['game_over']) {
                displayCurrentRoom($gameState);
            }
        } else {
            echo $result['message'] . "\n";
        }
    }
    
    displayGameOver($gameState);
}

// Start the game if running from CLI
if (php_sapi_name() === 'cli') {
    startGame();
}

?>
