<?php

namespace Jorgebyte\BasicCore\commands\home\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\manager\HomeManager;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class CreateCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("create", "create and save a position");
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

        if (strlen($homeName)> 10) {
            $sender->sendMessage($prefix . $this->plugin->getMessage("home_name_too_long", ["LIMIT" => 10]));
            Utils::addSound($sender, "note.bass");
            return;
        }

        if (HomeManager::createHome($sender, $homeName)) {
            $sender->sendMessage($prefix . $this->plugin->getMessage("home_create", ["HOME" => $homeName]));
            Utils::addSound($sender, "random.pop2");
        } else {
            $sender->sendMessage($prefix . $this->plugin->getMessage("home_exists", ["HOME" => $homeName]));
            Utils::addSound($sender, "random.bas");
        }
    }
}