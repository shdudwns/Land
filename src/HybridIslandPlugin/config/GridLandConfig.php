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

    // ✅ 소유자 확인
    public static function isOwner(string $playerName, string $targetName): bool {
        $island = self::getIsland($playerName);
        return isset($island['owner']) && strtolower($island['owner']) === strtolower($targetName);
    }

    // ✅ 소유자 변경
    public static function changeOwner(string $playerName, string $newOwner): void {
        $island = self::getIsland($playerName);
        if ($island !== null) {
            $island['owner'] = $newOwner;
            self::setIsland($playerName, $island);
        }
    }

    // ✅ 섬 확장 (크기 업데이트)
    public static function expandIsland(string $playerName, int $newSize): void {
        $island = self::getIsland($playerName);
        if ($island !== null) {
            $island['size'] = $newSize;
            self::setIsland($playerName, $island);
        }
    }

    // ✅ 섬 크기 가져오기
    public static function getIslandSize(string $playerName): int {
        $island = self::getIsland($playerName);
        return $island['size'] ?? 0;
    }

    // ✅ 홈 좌표 저장
    public static function setHome(string $playerName, array $homeCoords): void {
        self::$config->setNested("gridLands.$playerName.home", $homeCoords);
        self::$config->save();
    }

    // ✅ 홈 좌표 불러오기
    public static function getHome(string $playerName): ?array {
        return self::$config->getNested("gridLands.$playerName.home");
    }

    // ✅ 섬 존재 여부 확인
    public static function isIslandExists(string $playerName): bool {
        return self::getIsland($playerName) !== null;
    }
}
