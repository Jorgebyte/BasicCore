<?php

namespace Jorgebyte\BasicCore\events;

use Jorgebyte\BasicCore\api\CooldownAPI;
use Jorgebyte\BasicCore\api\EconomyAPI;
use Jorgebyte\BasicCore\api\KdrAPI;
use Jorgebyte\BasicCore\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;

class PlayerEvent implements Listener
{

    public function __construct(private Main $plugin){}

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $event->setJoinMessage($this->plugin->getMessage("player_join_broadcast", ["PLAYER" => $player->getName()]));

        // register
        KdrAPI::registerPlayer($player);
        EconomyAPI::registerPlayer($player);
    }

    public function onPlayerChat(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        $config = $this->plugin->getConfig();
        if (CooldownAPI::hasCooldown($player, "chat")) {
            $remaining = CooldownAPI::getRemainingTime($player, "chat");
            $player->sendMessage($this->plugin->getMessage("chat_cooldown_message", ["TIME" => $remaining]));
            $event->cancel();
            return;
        }
        CooldownAPI::startCooldown($player, "chat", $config->get("chat_cooldown"));
    }

    public function onPlayerKill(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();
        $killer = $player->getLastDamageCause()->getDamager();

        if ($killer instanceof Player) {
            KdrAPI::addKill($killer);
        }

        KdrAPI::addDeath($player);
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        $event->setQuitMessage($this->plugin->getMessage("player_quit_broadcast", ["PLAYER" => $player->getName()]));
    }
}