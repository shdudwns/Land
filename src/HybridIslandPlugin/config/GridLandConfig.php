<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class GridLandConfig {
    private static ?Config $config = null;
    private const LAND_SIZE = 32; // ✅ 고정된 섬 크기

    // ✅ Config 인스턴스 가져오기
    public static function getInstance(): Config {
        if (self::$config === null) {
            self::$config = ConfigManager::getConfig("gridLandData");
        }
        return self::$config;
    }

    public static function getAllIslands(): array {
        return self::getInstance()->get("gridLands", []);
    }

    public static function getIsland(string $playerName): ?array {
        return self::getInstance()->getNested("gridLands.$playerName");
    }

    public static function setIsland(string $playerName, array $data): void {
        self::getInstance()->setNested("gridLands.$playerName", $data);
        self::getInstance()->save();
    }

    public static function deleteIsland(string $playerName): void {
        self::getInstance()->removeNested("gridLands.$playerName");
        self::getInstance()->save();
    }

    // ✅ 소유자 확인
    public static function isOwner(string $playerName, string $targetName): bool {
        $island = self::getIsland($playerName);
        return isset($island['owner']) && strtolower($island['owner']) === strtolower($targetName);
    }

    // ✅ 섬 크기 고정
    public static function getIslandSize(): int {
        return self::LAND_SIZE;
    }

    // ✅ 홈 좌표 저장
    public static function setHome(string $playerName, array $homeCoords): void {
        self::getInstance()->setNested("gridLands.$playerName.home", $homeCoords);
        self::getInstance()->save();
    }

    // ✅ 홈 좌표 불러오기
    public static function getHome(string $playerName): ?array {
        return self::getInstance()->getNested("gridLands.$playerName.home");
    }

    // ✅ 섬 존재 여부 확인
    public static function isIslandExists(string $playerName): bool {
        return self::getIsland($playerName) !== null;
    }
}
