<?php

namespace Jorgebyte\BasicCore;

use Jorgebyte\BasicCore\api\CooldownAPI;
use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\api\KdrAPI;
use Jorgebyte\BasicCore\commands\DiscordCommand;
use Jorgebyte\BasicCore\commands\economy\EconomyCommand;
use Jorgebyte\BasicCore\commands\home\HomeCommand;
use Jorgebyte\BasicCore\commands\hub\HubCommand;
use Jorgebyte\BasicCore\commands\repair\RepairCommand;
use Jorgebyte\BasicCore\commands\stats\StatsCommand;
use Jorgebyte\BasicCore\events\PlayerEvent;
use Jorgebyte\BasicCore\manager\HomeManager;
use Jorgebyte\BasicCore\task\ScoreTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    private $messages;

    public function onEnable(): void
    {
        // APIS
        CooldownAPI::init($this->getDataFolder());
        KdrAPI::init($this->getDataFolder());
        EconomyAPI::init($this->getDataFolder());
        // Manager
        HomeManager::init($this->getDataFolder());

        $this->saveDefaultConfig();
        $this->initResources();
        $this->registerEvents();
        $this->registerTask();
        $this->registerCommands();
    }

    private function initResources(): void
    {
        $this->saveResource("config.yml");
        $this->saveResource("message.yml");
        $this->messages = new Config($this->getDataFolder() . "message.yml", Config::YAML);
    }

    /**
     * This function allows me to register commands
     *
     * @return void
     */
    private function registerCommands(): void
    {
        $commands =
            [
                new HubCommand($this),
                new RepairCommand($this),
                new DiscordCommand($this),
                new HomeCommand($this),
                new EconomyCommand($this),
                new StatsCommand($this)
            ];
        $this->getServer()->getCommandMap()->registerAll("basiccore", $commands);
    }

    /**
     * This function allows me to register events
     *
     * @return void
     */
    private function registerEvents(): void
    {
        $pluginManager = $this->getServer()->getPluginManager();

        $pluginManager->registerEvents(new PlayerEvent($this), $this);
    }

    /**
     * This function allows me to register task
     *
     * @return void
     */
    public function registerTask(): void
    {
        $map = $this->getScheduler();

        $map->scheduleRepeatingTask(new ScoreTask($this), 20);
    }

    /**
     * Get a message from the messages config
     *
     * @param string $key
     * @param array $replacements
     * @return string
     */
    public function getMessage(string $key, array $replacements = []): string
    {
        $message = $this->messages->get($key, "Message not found");

        foreach ($replacements as $search => $replace) {
            $message = str_replace("{" . $search . "}", $replace, $message);
        }
        return $message;
    }
}