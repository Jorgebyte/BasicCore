<?php

namespace Jorgebyte\BasicCore\commands\stats\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\api\KdrAPI;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TopDeathsCommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("topdeaths", "Displays top players by deaths");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $topDeaths = KdrAPI::getTopDeaths(10);

        $sender->sendMessage("Top 10 Players by Deaths:");
        foreach ($topDeaths as $index => $data) {
            $sender->sendMessage(TextFormat::YELLOW . ($index + 1) . ". " . TextFormat::WHITE . $data["name"] . ": " . $data["deaths"] . " deaths");
        }
    }

}