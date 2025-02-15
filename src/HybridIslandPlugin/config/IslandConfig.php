<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class IslandConfig {
    private static ?Config $config = null;  // ✅ 기본값 null로 초기화

    // ✅ Config 인스턴스 가져오기
    public static function getInstance(): Config {
        if (self::$config === null) {
            self::$config = ConfigManager::getConfig("islandData");
        }
        return self::$config;
    }

    public static function getAllIslands(): array {
        return self::getInstance()->get("islands", []);
    }

    public static function getIsland(string $playerName): ?array {
        return self::getInstance()->getNested("islands.$playerName");
    }

    public static function setIsland(string $playerName, array $data): void {
        self::getInstance()->setNested("islands.$playerName", $data);
        self::getInstance()->save();
    }

    public static function deleteIsland(string $playerName): void {
        self::getInstance()->removeNested("islands.$playerName");
        self::getInstance()->save();
    }

    public static function getAllIslands(): array {
        return self::$config->get("islands", []);
    }
}
