<?php

namespace Jorgebyte\BasicCore\commands\tpa;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;

class TpaCommand extends BaseCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "tpa", "Tpa Command");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

    }
}