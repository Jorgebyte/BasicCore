<?php

namespace Jorgebyte\BasicCore\utils;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

final class Utils
{

    const PERMS_PLAYER_COMMAND = "basiccore.player.command";
    const PERMS_OWNER_COMMAND = "basiccore.owner.command";
    const PERMS_VIPS_COMMAND = "basiccore.vips.command";

    public static function addSound(Player $player, string $soundName, float $volume = 1.0, float $pitch = 1.0): void
    {
        $player->getNetworkSession()->sendDataPacket(
            self::createPacket($player->getPosition(), $soundName, $volume, $pitch)
        );
    }

    /** @param Player[] $players */
    public static function addSoundBroadcast(array $players, string $soundName, float $volume = 1.0, float $pitch = 1.0): void
    {
        foreach ($players as $player) {
            if ($player instanceof Player) {
                self::addSound($player, $soundName, $volume, $pitch);
            }
        }
    }

    private static function createPacket(Vector3 $vec, string $soundName, float $volume = 1.0, float $pitch = 1.0): PlaySoundPacket
    {
        return PlaySoundPacket::create($soundName, $vec->x, $vec->y, $vec->z, $volume, $pitch);
    }

}