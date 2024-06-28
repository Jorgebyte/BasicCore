<?php

namespace Jorgebyte\BasicCore\commands\home\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\manager\HomeManager;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class DeleteCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("delete", "Delete a home position");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerArgument(0, new RawStringArgument("home"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

        $homeName = array_shift($args);
        $prefix = $this->plugin->getConfig()->get("prefix");
        if (HomeManager::deleteHome($sender, $homeName)) {
            $sender->sendMessage($prefix . $this->plugin->getMessage("home_remove"));
            Utils::addSound($sender, "random.orb");
        } else {
            $sender->sendMessage($prefix . $this->plugin->getMessage("home_remove_failed"));
            Utils::addSound($sender, "note.bass");
        }
    }
}