<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class SkyBlockConfig {
    private static ?Config $config = null;

    // ✅ 초기화 및 Config 로드
    public static function init(): void {
        if (self::$config === null) {
            self::$config = ConfigManager::getConfig("skyBlockData");
        }
    }

    // ✅ 모든 SkyBlock 목록 가져오기
    public static function getAllIslands(): array {
        self::init();
        return self::$config->get("skyBlocks", []);
    }

    // ✅ 특정 플레이어의 SkyBlock 정보 가져오기
    public static function getIsland(string $playerName): ?array {
        self::init();
        return self::$config->getNested("skyBlocks." . strtolower($playerName));
    }

    // ✅ SkyBlock 정보 저장
    public static function setIsland(string $playerName, array $data): void {
        self::init();
        self::$config->setNested("skyBlocks." . strtolower($playerName), $data);
        self::$config->save();
    }

    // ✅ SkyBlock 정보 삭제
    public static function deleteIsland(string $playerName): void {
        self::init();
        self::$config->removeNested("skyBlocks." . strtolower($playerName));
        self::$config->save();
    }

    // ✅ SkyBlock 존재 여부 확인
    public static function hasIsland(string $playerName): bool {
        return self::getIsland($playerName) !== null;
    }

    // ✅ 소유자 확인
    public static function isOwner(string $playerName, string $targetName): bool {
        $island = self::getIsland($playerName);
        return isset($island['owner']) && strtolower($island['owner']) === strtolower($targetName);
    }

    // ✅ SkyBlock 번호 가져오기
    public static function getIslandNumber(string $playerName): int {
        $island = self::getIsland($playerName);
        return $island['number'] ?? 0;
    }

    // ✅ SkyBlock 위치 정보 가져오기
    public static function getLocation(string $playerName): ?array {
        $island = self::getIsland($playerName);
        return $island['location'] ?? null;
    }
}
