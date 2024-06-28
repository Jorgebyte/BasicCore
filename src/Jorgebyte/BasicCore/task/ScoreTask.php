<?php

namespace Jorgebyte\BasicCore\task;

use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\api\KdrAPI;
use Jorgebyte\BasicCore\api\ScoreAPI;
use Jorgebyte\BasicCore\Main;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ScoreTask extends Task
{
    public function __construct(private Main $plugin){}

    public function onRun(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $this->updateScoreboard($player);
        }
    }

    private function updateScoreboard(Player $player): void
    {
        $scoreboardConfig = $this->plugin->getConfig()->get("scoreboard");
        $title = $scoreboardConfig["title"];
        $lines = $scoreboardConfig["lines"];

        ScoreAPI::new($player, "Objective", $title);

        $kills = KdrAPI::getKills($player);
        $deaths = KdrAPI::getDeaths($player);
        $kdr = KdrAPI::getKDR($player);
        $money = EconomyAPI::getMoney($player);
        $name = $player->getName();
        $date = date("d/m/Y");
        $day = date("d");
        $month = date("m");
        $year = date("Y");

        foreach ($lines as $index => $line) {
            $formattedLine = str_replace(
                ["{KILLS}", "{DEATHS}", "{KDR}", "{MONEY}", "{NAME}", "{DATE}", "{DAY}", "{MONTH}", "{YEAR}"],
                [$kills, $deaths, $kdr, $money, $name, $date, $day, $month, $year],
                $line
            );
            ScoreAPI::setLine($player, $index + 1, $formattedLine);
        }
    }
}