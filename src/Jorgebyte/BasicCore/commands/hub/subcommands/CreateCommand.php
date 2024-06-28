<?php

namespace Jorgebyte\BasicCore\commands\hub\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\BasicCore\Main;
use Jorgebyte\BasicCore\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class CreateCommand extends BaseSubCommand
{

    public function __construct(private Main $plugin)
    {
        parent::__construct("create", "create hub spawn");
        $this->setPermission(Utils::PERMS_OWNER_COMMAND);
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $position = $sender->getPosition();
        $world = $position->getWorld()->getDisplayName();
        $prefix = $this->plugin->getConfig()->get("prefix");

        $hubData = [
            "x" => $position->getX(), "y" => $position->getY(), "z" => $position->getZ(),
            "world" => $world
        ];
        $this->saveHubData($hubData);
        $sender->sendMessage($prefix . $this->plugin->getMessage("hub_create_success", [
            "x" => $position->getX(), "y" => $position->getY(), "z" => $position->getZ(),
            "world" => $world]));
        Utils::addSound($sender, "random.orb");
    }

    private function saveHubData(array $hubData): void {
        $config = new Config($this->plugin->getDataFolder() . "hub_data.json", Config::JSON);
        $config->set("hub", $hubData);
        try {
            $config->save();
        } catch (\JsonException) {
        }
    }
}