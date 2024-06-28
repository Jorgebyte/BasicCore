<?php

namespace Jorgebyte\BasicCore\commands\economy;

use CortexPE\Commando\BaseCommand;
use Jorgebyte\BasicCore\commands\economy\subcommands\GiveCommand;
use Jorgebyte\BasicCore\commands\economy\subcommands\PayCommand;
use Jorgebyte\BasicCore\commands\economy\subcommands\SeeCommand;
use Jorgebyte\BasicCore\commands\economy\subcommands\TakeCommand;
use Jorgebyte\BasicCore\commands\economy\subcommands\TopCommand;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class EconomyCommand extends BaseCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "economy", "Economy Command");
        $this->setAliases(["money"]);
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->registerSubCommand(new TopCommand());
        $this->registerSubCommand(new GiveCommand($this->plugin));
        $this->registerSubCommand(new TakeCommand($this->plugin));
        $this->registerSubCommand(new SeeCommand($this->plugin));
        $this->registerSubCommand(new PayCommand($this->plugin));

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage("Use /economy <subcommand>");
        Utils::addSound($sender, "random.pop");
    }
}