<?php

namespace Jorgebyte\BasicCore\commands\repair;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\commands\repair\subcommands\AllCommand;
use Jorgebyte\BasicCore\commands\repair\subcommands\ArmorCommand;
use Jorgebyte\BasicCore\commands\repair\subcommands\HandCommand;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class RepairCommand extends BaseCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "repair", "Repair Command");
        $this->setAliases(["fix"]);
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerSubCommand(new AllCommand($this->plugin));
        $this->registerSubCommand(new ArmorCommand($this->plugin));
        $this->registerSubCommand(new HandCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{}
}