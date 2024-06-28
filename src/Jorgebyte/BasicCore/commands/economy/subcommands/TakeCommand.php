<?php

namespace Jorgebyte\BasicCore\commands\economy\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TakeCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("take", "remove money from the player");
        $this->setPermission(Utils::PERMS_OWNER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefix = $this->plugin->getConfig()->get("prefix");
        $target = $this->plugin->getServer()->getPlayerExact($args["player"]);
        $amount = $args["amount"];

        if ($target instanceof Player) {
            EconomyAPI::removeMoney($target, $amount);
            $sender->sendMessage($prefix . $this->plugin->getMessage("take_success",
                    ["PLAYER" => $target->getName(), "AMOUNT" => $amount]));
            Utils::addSound($sender, "random.orb");
        } else {
            $sender->sendMessage("Player not found.");
        }
    }
}