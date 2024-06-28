# BasicCore

**BasicCore** is a versatile plugin designed for factions servers, offering essential features and APIs to enhance server functionality. Developers can leverage this project as a foundation for building their own plugins.

## Features

BasicCore provides several built-in features and commands to jump-start your server setup:

- **Home Management**: Set, teleport to, and manage player homes.
- **Economy System**: Comprehensive economy commands for managing in-game currency.
- **KDR (Kill/Death Ratio) Tracking**: Commands to track and display player KDR statistics.
- **Lobby Management**: Commands to save and teleport to lobby locations.
- **Item Repair**: Commands for repairing items.

## APIs Included

For developers, BasicCore includes a suite of APIs that can be utilized to extend and customize server functionality:

1. **Cooldown API**: Manage cooldown periods for commands or actions.
2. **KDR API**: Track and manipulate player kills, deaths, and KDR statistics.
3. **ScoreBoard API**: Create and manage custom scoreboards for players.
4. **Economy API**: Manage player balances, transactions, and top player listings.

## Commands

Here are some of the commands included in BasicCore:

- **Home Commands**: Set, teleport to, and delete player homes.
- **Economy Commands**: Check balance, pay other players, and view top balances.
- **KDR Commands**: View KDR statistics and leaderboards.
- **Lobby Commands**: Set and teleport to the server lobby.
- **Repair Commands**: Repair items in player inventories.

## API
```php
# Coldown #
// Starts a cooldown for a specified action and duration
public static function startCooldown(Player $player, string $action, int $duration): void

// Checks if a player has an active cooldown for a specified action.
public static function hasCooldown(Player $player, string $action): bool

// Gets the remaining cooldown time for a specified action.
public static function getRemainingTime(Player $player, string $action): int
# ---------------------------- #
# EconomyAPI #
// Registers a player in the economy system.
public static function registerPlayer(Player $player): void

// Adds money to a player's balance.
public static function addMoney(Player $player, int $amount): void

// Removes money from a player's balance.
public static function removeMoney(Player $player, int $amount): void

// Gets a player's balance.
public static function getMoney(Player $player): int

// Gets the top 10 players with the most money.
public static function getTopPlayers(): array
# ---------------------------- #
# KDRAPI #
// Registers a player in the KDR system.
public static function registerPlayer(Player $player): void

// Adds a kill to a player's statistics.
public static function addKill(Player $player): void

// Removes a kill from a player's statistics.
public static function removeKill(Player $player): void

// Adds a death to a player's statistics.
public static function addDeath(Player $player): void

// Removes a death from a player's statistics.
public static function removeDeath(Player $player): void

// Gets the number of kills a player has.
public static function getKills(Player $player): int

// Gets the number of deaths a player has.
public static function getDeaths(Player $player): int

// Gets the kill-death ratio (KDR) for a player.
public static function getKDR(Player $player): float

// Gets the top players by kills.
public static function getTopKills(int $limit): array

// Gets the top players by deaths.
public static function getTopDeaths(int $limit): array
# ---------------------------- #
# ScoreAPI #
// Removes the scoreboard from a player.
public static function remove(Player $sender): void

// Creates a new scoreboard for a player.
public static function new(Player $sender, string $objectiveName, string $displayName): void

// Creates a new scoreboard for a player.
public static function setLine(Player $sender, int $score, string $message): void
```

## Dependencies
- [Forms](https://github.com/Frago9876543210/forms)


# Contact
[![Discord Presence](https://lanyard.cnrad.dev/api/1165097093480853634?theme=dark&bg=005cff&animated=false&hideDiscrim=true&borderRadius=30px&idleMessage=Hello%20boys%20and%20girls)](https://discord.com/users/1165097093480853634)