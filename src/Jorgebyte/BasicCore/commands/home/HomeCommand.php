<?php

namespace Jorgebyte\BasicCore\commands\home;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\commands\home\subcommands\CreateCommand;
use Jorgebyte\BasicCore\commands\home\subcommands\DeleteCommand;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\manager\HomeManager;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class HomeCommand extends BaseCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "home", "Home Command");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerSubCommand(new CreateCommand($this->plugin));
        $this->registerSubCommand(new DeleteCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
       HomeManager::sendFormMain($sender);
    }
}