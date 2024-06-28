<?php

namespace Jorgebyte\BasicCore\api;

use pocketmine\player\Player;
use pocketmine\utils\Config;

class KdrAPI
{

    private static string $dataFolder;

    /**
     * Initializes the data folder for KDR files.
     *
     * @param string $dataFolder The base data folder path.
     */
    public static function init(string $dataFolder): void
    {
        self::$dataFolder = $dataFolder . "kdr/";
        if (!is_dir(self::$dataFolder)) {
            mkdir(self::$dataFolder);
        }
    }

    /**
     * Registers a player in the KDR system by creating a JSON file with default values if it doesn't exist.
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
                "kills" => 0,
                "deaths" => 0
            ]);
            $config->save();
        }
    }

    /**
     * Adds a kill to the player's statistics.
     *
     * @param Player $player The player to add a kill to.
     */
    public static function addKill(Player $player): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $stats = $config->getAll();

        $stats["kills"] += 1;
        $config->setAll($stats);
        $config->save();
    }

    /**
     * Removes a kill from the player's statistics if they have any.
     *
     * @param Player $player The player to remove a kill from.
     */
    public static function removeKill(Player $player): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $stats = $config->getAll();

        if ($stats["kills"] > 0) {
            $stats["kills"] -= 1;
        }
        $config->setAll($stats);
        $config->save();
    }

    /**
     * Adds a death to the player's statistics.
     *
     * @param Player $player The player to add a death to.
     */
    public static function addDeath(Player $player): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $stats = $config->getAll();

        $stats["deaths"] += 1;
        $config->setAll($stats);
        $config->save();
    }

    /**
     * Removes a death from the player's statistics if they have any.
     *
     * @param Player $player The player to remove a death from.
     */
    public static function removeDeath(Player $player): void
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $stats = $config->getAll();

        if ($stats["deaths"] > 0) {
            $stats["deaths"] -= 1;
        }
        $config->setAll($stats);
        $config->save();
    }

    /**
     * Gets the number of kills the player has.
     *
     * @param Player $player The player to get the kills for.
     * @return int The number of kills the player has.
     */
    public static function getKills(Player $player): int
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $stats = $config->getAll();

        return $stats["kills"] ?? 0;
    }

    /**
     * Gets the number of deaths the player has.
     *
     * @param Player $player The player to get the deaths for.
     * @return int The number of deaths the player has.
     */
    public static function getDeaths(Player $player): int
    {
        $playerName = $player->getName();
        $playerFile = self::$dataFolder . "$playerName.json";

        $config = new Config($playerFile, Config::JSON);
        $stats = $config->getAll();

        return $stats["deaths"] ?? 0;
    }

    /**
     * Gets the kill-death ratio (KDR) for the player.
     *
     * @param Player $player The player to get the KDR for.
     * @return float The KDR of the player.
     */
    public static function getKDR(Player $player): float
    {
        $kills = self::getKills($player);
        $deaths = self::getDeaths($player);

        return $deaths === 0 ? (float)$kills : round($kills / $deaths, 2);
    }

    /**
     * Gets the top players by kills.
     *
     * @param int $limit The number of top players to retrieve.
     * @return array An array of player data sorted by kills.
     */
    public static function getTopKills(int $limit): array
    {
        $players = [];
        foreach (glob(self::$dataFolder . "*.json") as $file) {
            $playerName = basename($file, ".json");
            $config = new Config($file, Config::JSON);
            $stats = $config->getAll();
            $players[] = [
                "name" => $playerName,
                "kills" => $stats["kills"] ?? 0
            ];
        }

        usort($players, function ($a, $b) {
            return $b["kills"] <=> $a["kills"];
        });

        return array_slice($players, 0, $limit);
    }

    /**
     * Gets the top players by deaths.
     *
     * @param int $limit The number of top players to retrieve.
     * @return array An array of player data sorted by deaths.
     */
    public static function getTopDeaths(int $limit): array
    {
        $players = [];
        foreach (glob(self::$dataFolder . "*.json") as $file) {
            $playerName = basename($file, ".json");
            $config = new Config($file, Config::JSON);
            $stats = $config->getAll();
            $players[] = [
                "name" => $playerName,
                "deaths" => $stats["deaths"] ?? 0
            ];
        }

        usort($players, function ($a, $b) {
            return $b["deaths"] <=> $a["deaths"];
        });

        return array_slice($players, 0, $limit);
    }
}