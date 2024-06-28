<?php

namespace Jorgebyte\BasicCore\manager;

use forms\menu\Button;
use forms\MenuForm;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class HomeManager
{

    private static string $dataFolder;

    public static function init(string $dataFolder): void
    {
        self::$dataFolder = $dataFolder . "homes/";
        if(!is_dir(self::$dataFolder)){
            mkdir(self::$dataFolder);
        }
    }

    public static function createHome(Player $player, string $homeName): bool
    {
        $playerName = $player->getName();
        $homeFile = self::$dataFolder . "$playerName.json";

        $config = new Config($homeFile, Config::JSON);
        $homes = $config->getAll();

        if (isset($homes[$homeName])) {
            return false;
        }

        $homes[$homeName] = [
            "x" => $player->getPosition()->getX(),
            "y" => $player->getPosition()->getY(),
            "z" => $player->getPosition()->getZ(),
            "world" => $player->getWorld()->getFolderName()
        ];

        $config->setAll($homes);
        $config->save();

        return true;
    }

    public static function getHomes(Player $player): array
    {
        $playerName = $player->getName();
        $homeFile = self::$dataFolder . "$playerName.json";

        $config = new Config($homeFile, Config::JSON);
        return $config->getAll();
    }

    public static function deleteHome(Player $player, string $homeName): bool
    {
        $playerName = $player->getName();
        $homeFile = self::$dataFolder . "$playerName.json";

        $config = new Config($homeFile, Config::JSON);
        $homes = $config->getAll();

        if (!isset($homes[$homeName])) {
            return false;
        }

        unset($homes[$homeName]);
        $config->setAll($homes);
        $config->save();

        return true;
    }

    public static function sendFormMain(Player $player)
    {
        $homes = self::getHomes($player);

        $buttons = [];
        foreach ($homes as $homeName => $homeData) {
            $buttons[] = new Button($homeName);
        }

        $form = new MenuForm("Select Home", "Choose a home to teleport to:", $buttons, function (Player $player, Button $selected) use ($homes): void {
            $homeName = $selected->text;
            $homeData = $homes[$homeName];

            $world = $player->getServer()->getWorldManager()->getWorldByName($homeData['world']);
            if ($world === null) {
                $player->sendMessage("The world {$homeData['world']} is not loaded.");
                return;
            }

            $position = new Position($homeData['x'], $homeData['y'], $homeData['z'], $world);
            $player->teleport($position);
        });
        $player->sendForm($form);
    }
}