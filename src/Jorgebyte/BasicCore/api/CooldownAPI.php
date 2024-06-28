<?php

namespace Jorgebyte\BasicCore\api;

use pocketmine\player\Player;
use pocketmine\utils\Config;

class CooldownAPI
{

    private static string $dataFolder;
    private static array $cooldowns = [];

    public static function init(string $dataFolder): void
    {
        self::$dataFolder = $dataFolder . "cooldowns/";
        if(!is_dir(self::$dataFolder)){
            mkdir(self::$dataFolder);
        }
        self::loadCooldowns();
    }

    public static function startCooldown(Player $player, string $action, int $duration): void
    {
        $name = strtolower($player->getName());
        self::$cooldowns[$name][$action] = time() + $duration;
        self::saveCooldown($name);
    }

    public static function hasCooldown(Player $player, string $action): bool
    {
        $name = strtolower($player->getName());
        if (isset(self::$cooldowns[$name][$action])) {
            if (time() > self::$cooldowns[$name][$action]) {
                unset(self::$cooldowns[$name][$action]);
                self::saveCooldown($name);
                return false;
            }
            return true;
        }
        return false;
    }

    public static function getRemainingTime(Player $player, string $action): int
    {
        $name = strtolower($player->getName());
        return isset(self::$cooldowns[$name][$action]) ? self::$cooldowns[$name][$action] - time() : 0;
    }

    private static function loadCooldowns(): void
    {
        foreach (glob(self::$dataFolder . "*.json") as $file) {
            $playerName = basename($file, ".json");
            $config = new Config($file, Config::JSON);
            self::$cooldowns[$playerName] = $config->getAll();
        }
    }

    private static function saveCooldown(string $playerName): void
    {
        $config = new Config(self::$dataFolder . "$playerName.json", Config::JSON);
        $config->setAll(self::$cooldowns[$playerName] ?? []);
        $config->save();
    }
}