<?php

namespace Jorgebyte\BasicCore\commands\repair\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\item\Armor;
use pocketmine\item\Tool;

class AllCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("all", "Repair all your inventory");
        $this->setPermission(Utils::PERMS_VIPS_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $inventory = $sender->getInventory();
        $armorInventory = $sender->getArmorInventory();
        $prefix = $this->plugin->getConfig()->get("prefix");

        foreach ($inventory->getContents() as $slot => $item) if ($item instanceof Tool && $item->getMaxDurability() > 0) {
            $item->setDamage(0);
            $inventory->setItem($slot, $item);
        }

        foreach ($armorInventory->getContents() as $slot => $item) if ($item instanceof Armor && $item->getMaxDurability() > 0) {
            $item->setDamage(0);
            $armorInventory->setItem($slot, $item);
        }
        $sender->sendMessage($prefix . $this->plugin->getMessage("repair_success_all"));
        Utils::addSound($sender, "random.orb");
    }
}