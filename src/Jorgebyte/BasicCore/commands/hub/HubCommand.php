<?php

namespace Jorgebyte\BasicCore\commands\hub;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\commands\hub\subcommands\CreateCommand;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\World;

class HubCommand extends BaseCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "hub", "Hub Command");
        $this->setAliases(["lobby", "spawn"]);
        $this->setPermission(Utils::PERMS_PLAYER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerSubCommand(new CreateCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $config = new Config($this->plugin->getDataFolder() . "hub_data.json", Config::JSON);
        $hubData = $config->get("hub", null);
        $prefix = $this->plugin->getConfig()->get("prefix");

        if ($hubData === null) {
            $sender->sendMessage($prefix . $this->plugin->getMessage("hub_not_set"));
            Utils::addSound($sender, "note.bass");
            return;
        }
        $world = $this->plugin->getServer()->getWorldManager()->getWorldByName($hubData["world"]);
        if ($world instanceof  World) {
            $position = new Position($hubData["x"], $hubData["y"], $hubData["z"], $world);
            $sender->teleport($position);
            $sender->sendMessage($prefix . $this->plugin->getMessage("hub_teleport_success"));
            Utils::addSound($sender, "random.orb");
        } else {
            $sender->sendMessage($prefix . $this->plugin->getMessage("hub_world_not_found", [
                    "world" => $hubData["world"]]));
            Utils::addSound($sender, "note.bass");
        }
    }
}