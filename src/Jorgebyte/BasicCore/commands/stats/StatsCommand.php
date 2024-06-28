<?php

namespace Jorgebyte\BasicCore\commands\stats;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\api\KdrAPI;
use Jorgebyte\BasicCore\commands\stats\subcommands\TopDeathsCommand;
use Jorgebyte\BasicCore\commands\stats\subcommands\TopKillsCommand;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class StatsCommand extends BaseCommand
{
    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "stats", "Stast Command");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerSubCommand(new TopKillsCommand());
        $this->registerSubCommand(new TopDeathsCommand());
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

        $kills = KdrAPI::getKills($sender);
        $deaths = KdrAPI::getDeaths($sender);
        $kdr = KdrAPI::getKDR($sender);

        $sender->sendMessage("Your Stats:");
        $sender->sendMessage(TextFormat::YELLOW . "Kills: " . TextFormat::WHITE . $kills);
        $sender->sendMessage(TextFormat::YELLOW . "Deaths: " . TextFormat::WHITE . $deaths);
        $sender->sendMessage(TextFormat::YELLOW . "KDR: " . TextFormat::WHITE . $kdr);
    }
}