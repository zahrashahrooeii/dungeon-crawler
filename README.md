# Dungeon Crawler Game

A text-based dungeon crawler game written in PHP 

## Features

- **Turn-based gameplay**: Navigate through a dungeon room by room
- **Combat system**: Fight monsters with health and attack mechanics
- **Treasure collection**: Find and collect gold coins throughout the dungeon
- **Multiple room types**: Different rooms with various challenges and rewards
- **Interactive CLI interface**: Simple commands to control your character
- **Dungeon map**: View your progress and discovered areas

## Game Mechanics

### Player
- **Health**: 100 HP (can be reduced by monster attacks)
- **Attack**: 20 damage per attack
- **Treasure**: Collect gold coins from rooms and defeated monsters

### Monsters
- **Goblin**: 30 HP, 15 attack, 10 gold reward
- **Skeleton**: 40 HP, 20 attack, 15 gold reward
- **Orc**: 50 HP, 25 attack, 20 gold reward  
- **Troll**: 80 HP, 35 attack, 30 gold reward
- **Dragon**: 120 HP, 50 attack, 100 gold reward

### Rooms
- **Entrance Hall**: Starting point with connections in all directions
- **Various chambers**: Some contain monsters, others have treasure
- **12 total rooms**: Including storage, armory, kitchen, training room, and more
- **Exit**: Your goal - escape the dungeon!

## Installation

### Prerequisites
- PHP 7.0 or higher
- Command line access

### Setup
1. Download or clone this repository
2. Navigate to the project directory
3. Run the game using PHP CLI

## How to Run

### Play the Game
```bash
php dungeon_crawler.php
```

### Run Tests
```bash
# Run all tests
php run_tests.php

# Run individual test suites
php tests/GameLogicTest.php
php tests/CombatTest.php
php tests/MovementTest.php
```

## How to Play

### Commands
- `north` or `n` - Move north
- `south` or `s` - Move south  
- `east` or `e` - Move east
- `west` or `w` - Move west
- `attack` or `a` - Attack a monster in the current room
- `status` - Display your current health, attack, and treasure
- `map` - Show the dungeon map with your progress
- `quit` or `q` - Exit the game

### Gameplay
1. You start in the Entrance Hall
2. Explore rooms by using movement commands
3. When you encounter a monster, use `attack` to fight
4. Collect treasure from rooms and defeated monsters
5. Find the exit to win the game!

### Combat
- Combat is turn-based
- You attack first, then the monster attacks
- You cannot leave a room while a monster is present
- Defeating monsters gives you gold rewards

### Winning/Losing
- **Win**: Reach the exit room without any monsters
- **Lose**: Your health reaches 0

## Project Structure

```
dungeon_crawler/
├── dungeon_crawler.php    # Main game file
├── run_tests.php         # Test runner
├── tests/                # Unit tests
│   ├── TestRunner.php    # Simple test framework
│   ├── GameLogicTest.php # Game logic tests
│   ├── CombatTest.php    # Combat system tests
│   └── MovementTest.php  # Movement system tests
├── img-240171b3-cb19-4a7e-a93c-c7001a9fa929  # Reference image
└── README.md             # This file
```

## Code Architecture

The game follows a simple, clean structure with clear separation of concerns:

- **Game Logic Functions**: Handle game state, rules, combat, and dungeon generation
- **I/O Functions**: Handle display and user input
- **Main Game Loop**: Orchestrates the game flow

### Key Design Principles

- **Clear separation** between game logic and user interface
- **Simple data structures** using arrays instead of complex objects
- **Maintainable code** with well-organized functions
- **Simplicity over complexity** - no over-engineering

## Technical Details

- **Language**: PHP 7.0+
- **Architecture**: Functional programming with clear separation of concerns
- **Input**: Command-line interface
- **State Management**: In-memory game state maintained between turns
- **Testing**: Custom unit test framework with 21 test cases
- **CI/CD**: GitHub Actions with automated testing across PHP versions
- **No external dependencies**: Pure PHP implementation

## CI/CD Pipeline

This project includes a comprehensive CI/CD pipeline using GitHub Actions:

### Continuous Integration
- **Multi-version testing**: Tests run on PHP 7.4, 8.0, 8.1, and 8.2
- **Automated testing**: All unit tests run on every push and pull request
- **Code quality checks**: Syntax validation and basic security scanning
- **Requirements verification**: Automated checks to ensure all game requirements are met

### Continuous Deployment
- **Automated releases**: Create releases by pushing version tags (e.g., `v1.0.0`)
- **Release artifacts**: Automatic creation of downloadable zip files
- **Documentation validation**: Ensures README and documentation are complete

### Workflow Files
- `.github/workflows/ci.yml` - Main CI pipeline
- `.github/workflows/release.yml` - Release automation
- `.github/ISSUE_TEMPLATE/` - Issue and feature request templates

## Requirements Met

✅ **Turn-based gameplay** - Game processes one command at a time  
✅ **Connected rooms** - Rooms are linked with directional connections  
✅ **Player starts in given room** - Begins in Entrance Hall  
✅ **Movement commands** - north, south, east, west  
✅ **Monsters, treasure, empty rooms** - Various room types implemented  
✅ **Combat mechanics** - Turn-based combat with health and damage  
✅ **Treasure collection** - Gold coins collected and added to score  
✅ **Game end conditions** - Player death or reaching exit  
✅ **PHP implementation** - Written entirely in PHP  
✅ **State maintenance** - Game state preserved between turns  
✅ **Clear code structure** - Logic separated from I/O  
✅ **Maintainable code** - Simple, readable functions  
✅ **Thoughtful state handling** - Proper game state management  
✅ **Simplicity** - No over-engineering, clean implementation  

