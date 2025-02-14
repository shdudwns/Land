<?php

namespace HybridIslandPlugin\config;

use pocketmine\utils\Config;
use HybridIslandPlugin\Main;

class ConfigManager {

    private static array $configs = [];

    // ✅ 초기화 및 Config 파일 생성
    public static function init(): void {
        self::loadConfig("islandData");
        self::loadConfig("gridLandData");
        self::loadConfig("skyBlockData");
        self::loadConfig("messages");
    }

    // ✅ Config 파일 로드 및 생성
    public static function loadConfig(string $fileName): Config {
        $filePath = Main::getInstance()->getDataFolder() . $fileName . ".yml";

        // 파일이 없으면 기본값으로 새로 생성
        if (!file_exists($filePath)) {
            $defaultData = [];

            switch ($fileName) {
                case "islandData":
                    $defaultData = ["islands" => []];
                    break;
                case "gridLandData":
                    $defaultData = ["gridLands" => []];
                    break;
                case "skyBlockData":
                    $defaultData = ["skyBlocks" => []];
                    break;
                case "messages":
                    $defaultData = [
                        "messages" => [
                            "island" => [
                                "create_success" => "섬이 성공적으로 생성되었습니다!",
                                "delete_success" => "섬이 삭제되었습니다.",
                                "no_permission" => "이 명령어를 사용할 권한이 없습니다.",
                                "not_found" => "해당 섬을 찾을 수 없습니다."
                            ]
                        ]
                    ];
                    break;
            }

            $config = new Config($filePath, Config::YAML, $defaultData);
            $config->save();
        }

        self::$configs[$fileName] = new Config($filePath, Config::YAML);
        return self::$configs[$fileName];
    }

    // ✅ Config 가져오기
    public static function getConfig(string $fileName): Config {
        return self::$configs[$fileName] ?? self::loadConfig($fileName);
    }

    // ✅ Config 저장하기
    public static function saveConfig(string $fileName): void {
        if (isset(self::$configs[$fileName])) {
            self::$configs[$fileName]->save();
        }
    }

    public static function saveAll(): void {
        foreach (self::$configs as $config) {
            $config->save();
        }
    }
}
