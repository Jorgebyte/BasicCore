<?php

namespace Jorgebyte\BasicCore\commands\economy\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class TopCommand extends baseSubCommand
{
    public function __construct()
    {
        parent::__construct("top", "see the top 10 with the most money");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);

    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $topPlayers = EconomyAPI::getTopPlayers();
        $sender->sendMessage("Top 10 Players:");
        foreach ($topPlayers as $rank => $data) {
            $message = ($rank + 1) . ". " . $data['name'] . " - $" . number_format($data['money']);
            $sender->sendMessage($message);
            Utils::addSound($sender, "random.pop");
        }
    }
}