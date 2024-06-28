<?php

namespace Jorgebyte\BasicCore\commands;

use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class DiscordCommand extends Command
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("discord", "Discord Command");
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        if (!$sender instanceof Player) return;

        $config = $this->plugin->getConfig();
        $sender->sendMessage($config->get("discord"));
        Utils::addSound($sender, "random.pop");
    }
}

