<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class IslandConfig {
    private static Config $config;

    public static function init(): void {
        if (!isset(self::$config)) { 
            self::$config = ConfigManager::getConfig("islandData");
        }
    }

    public static function getAllIslands(): array {
        return self::$config->get("islands", []);
    }

    public static function getIsland(string $playerName): ?array {
        return self::$config->getNested("islands.$playerName");
    }

    public static function setIsland(string $playerName, array $data): void {
        self::$config->setNested("islands.$playerName", $data);
        self::$config->save();
    }

    public static function deleteIsland(string $playerName): void {
        self::$config->removeNested("islands.$playerName");
        self::$config->save();
    }
}
