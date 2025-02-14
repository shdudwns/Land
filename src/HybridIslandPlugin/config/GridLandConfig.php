<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class GridLandConfig {
    private static Config $config;

    public static function init(): void {
        self::$config = ConfigManager::getConfig("gridLandData");
    }

    public static function getAllIslands(): array {
        return self::$config->get("gridLands", []);
    }

    public static function getIsland(string $playerName): ?array {
        return self::$config->getNested("gridLands.$playerName");
    }

    public static function setIsland(string $playerName, array $data): void {
        self::$config->setNested("gridLands.$playerName", $data);
        self::$config->save();
    }

    public static function deleteIsland(string $playerName): void {
        self::$config->removeNested("gridLands.$playerName");
        self::$config->save();
    }
}
