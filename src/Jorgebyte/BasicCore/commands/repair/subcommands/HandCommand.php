<?php

namespace Jorgebyte\BasicCore\commands\repair\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\item\Armor;
use pocketmine\item\Tool;

class HandCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("hand", "repair the item in your hand");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $item = $sender->getInventory()->getItemInHand();
        $prefix = $this->plugin->getConfig()->get("prefix");

        if (($item instanceof Tool || $item instanceof Armor) && $item->getMaxDurability() > 0) {
            $item->setDamage(0);
            $sender->getInventory()->setItemInHand($item);
            $sender->sendMessage($prefix . $this->plugin->getMessage("repair_success_hand"));
            Utils::addSound($sender, "random.orb");
        } else {
            $sender->sendMessage($prefix . $this->plugin->getMessage("repair_error_hand"));
            Utils::addSound($sender, "note.bass");
        }
    }
}