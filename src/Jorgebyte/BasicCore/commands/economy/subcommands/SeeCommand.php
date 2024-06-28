<?php

namespace Jorgebyte\BasicCore\commands\economy\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SeeCommand extends BaseSubCommand
{
    public function __construct(private Main $plugin)
    {
        parent::__construct("see", "see player money");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefix = $this->plugin->getConfig()->get("prefix");
        $target = $this->plugin->getServer()->getPlayerExact($args["player"]);

        if ($target instanceof Player) {
            $amount = EconomyAPI::getMoney($target);
            $sender->sendMessage($prefix . $this->plugin->getMessage("see_success",
                    ["PLAYER" => $target->getName(), "AMOUNT" => $amount]));
            Utils::addSound($sender, "random.pop");
        } else {
            $sender->sendMessage("Player not found.");
        }
    }
}