<?php

namespace Jorgebyte\BasicCore\api;

use pocketmine\player\Player;
use pocketmine\utils\Config;

class EconomyAPI
{

    private static string $dataFolder;

    /**
     * Initializes the data folder for economy files.
     *
     * @param string $dataFolder The base data folder path.
     */
    public static function init(string $dataFolder): void
    {
        self::$dataFolder = $dataFolder . "economy/";
        if (!is_dir(self::$dataFolder)) {
            mkdir(self::$dataFolder);
        }
    }

    /**
     * Registers a player in the economy system by creating a JSON file with default values if it doesn't exist.
     *
     * @param Player $player The player to register.
     */
    public static function registerPlayer(Player $player): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        if (!file_exists($playerFile)) {
            $config = new Config($playerFile, Config::JSON);
            $config->setAll([
                "money" => 0
            ]);
            $config->save();
        }
    }

    /**
     * Adds money to the player's balance.
     *
     * @param Player $player The player to add money to.
     * @param int $amount The amount of money to add.
     */
    public static function addMoney(Player $player, int $amount): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $balance = $config->get("money", 0);

        $balance += $amount;
        $config->set("money", $balance);
        $config->save();
    }

    /**
     * Removes money from the player's balance.
     *
     * @param Player $player The player to remove money from.
     * @param int $amount The amount of money to remove.
     */
    public static function removeMoney(Player $player, int $amount): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $balance = $config->get("money", 0);

        $balance -= $amount;
        if ($balance < 0) {
            $balance = 0;
        }
        $config->set("money", $balance);
        $config->save();
    }

    /**
     * Gets the player's balance.
     *
     * @param Player $player The player to get the balance for.
     * @return int The player's balance.
     */
    public static function getMoney(Player $player): int
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        return $config->get("money", 0);
    }

    /**
     * Gets the top 10 players with the most money.
     *
     * @return array The top 10 players and their balances.
     */
    public static function getTopPlayers(): array
    {
        $players = [];

        foreach (glob(self::$dataFolder . "*.json") as $file) {
            $playerName = basename($file, ".json");
            $config = new Config($file, Config::JSON);
            $players[] = [
                "name" => $playerName,
                "money" => $config->get("money", 0)
            ];
        }

        usort($players, function ($a, $b) {
            return $b['money'] <=> $a['money'];
        });

        return array_slice($players, 0, 10);
    }
}