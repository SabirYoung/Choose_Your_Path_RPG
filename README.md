# Choose_Your_Path_RPG

# Text-Based RPG

## Project Overview
A PHP-driven text adventure where player choices can shape the story's outcome. Features character creation, branching narrative, inventory system, stat-based choices, and multiple endings.

## Features Implemented

### Core Features (4)
1. **Cookies & Sessions - Leaderboard**
   - Session-based score tracking
   - Real-time leaderboard updates
   - Top scores displayed with usernames and endings

2. **Form Processing**
   - Registration and login forms with validation
   - Character creation form
   - Choice selection forms
   - Input sanitization using htmlspecialchars() and filter_input()

3. **Login System**
   - User registration with password hashing
   - Session-based authentication
   - Protected game pages
   - Secure logout functionality

4. **Game Logic**
   - 15+ story nodes in nested array structure
   - 4 unique endings (Heroic Victory, Tragic Failure, Secret Path, Dark Lord)
   - Stat-based choice requirements
   - Inventory management system

### Additional Features (All 4 - Graduate Level)
1. **AI Story Advisor**
   - Consequence previews for each choice
   - Personalized stat requirement feedback
   - Shows exactly what's needed to unlock choices

2. **Story Path Tracker**
   - Visual sidebar showing all major choices
   - Timestamp for each decision
   - Persistent throughout gameplay

3. **Dynamic Class Flavoring**
   - Class-specific text variants (Warrior/Mage/Rogue)
   - Different narrative perspective based on character class

4. **Hero Summary Generation**
   - Dynamic ending summary from session data
   - Includes major choices, alignment, and stats
   - Personalized to player's journey


## AI Usage Disclosure
This project uses AI assistance for:
- Story content generation and narrative structure
- CSS animation suggestions
- Code organization recommendations


## How to Run
1. Place all files in your web server directory
2. Ensure PHP 7.4+ is installed
3. Navigate to index.php
4. Register a new account
5. Create your character and begin the adventure

## Game Mechanics
- **Classes**: Warrior (high strength), Mage (high magic), Rogue (high agility)
- **Stats**: Health, Strength, Magic, Agility
- **Alignment**: Tracks moral choices (-10 to +10)
- **Inventory**: Collect items to unlock special paths
- **Score**: Earned through choices and endings

## Team Members
1. Sabir Young

## codd link
https://codd.cs.gsu.edu/~syoung95/web/Group_Project/2/