<?php

namespace Jorgebyte\BasicCore\commands\economy\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PayCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("pay", "Pay another player money");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefix = $this->plugin->getConfig()->get("prefix");
        $target = $this->plugin->getServer()->getPlayerExact($args["player"]);
        $amount = $args["amount"];

        if ($amount <= 0) {
            $sender->sendMessage($prefix . "Amount must be positive.");
            return;
        }

        if ($target instanceof Player) {
            $senderMoney = EconomyAPI::getMoney($sender);

            if ($senderMoney < $amount) {
                $sender->sendMessage($prefix . "You do not have enough money to complete this transaction.");
                return;
            }

            EconomyAPI::removeMoney($sender, $amount);
            EconomyAPI::addMoney($target, $amount);

            $sender->sendMessage($prefix . $this->plugin->getMessage("success_pay", ["AMOUNT" => $amount, "PLAYER" => $target->getName()]));
            Utils::addSound($sender, "random.orb");
            $target->sendMessage($prefix . $this->plugin->getMessage("success_pay_target", ["AMOUNT" => $amount, "PLAYER" => $target->getName()]));
            Utils::addSound($target, "random.orb");
        } else {
            $sender->sendMessage("Player not found.");
        }
    }
}