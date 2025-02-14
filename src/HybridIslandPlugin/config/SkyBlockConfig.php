<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class SkyBlockConfig {
    private static Config $config;

    public static function init(): void {
        self::$config = ConfigManager::getConfig("skyBlockData");
    }

    public static function getAllIslands(): array {
        return self::$config->get("skyBlocks", []);
    }

    public static function getIsland(string $playerName): ?array {
        return self::$config->getNested("skyBlocks.$playerName");
    }

    public static function setIsland(string $playerName, array $data): void {
        self::$config->setNested("skyBlocks.$playerName", $data);
        self::$config->save();
    }

    public static function deleteIsland(string $playerName): void {
        self::$config->removeNested("skyBlocks.$playerName");
        self::$config->save();
    }
}
