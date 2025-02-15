<?php

namespace HybridIslandPlugin\world;

use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\Server;
use HybridIslandPlugin\config\ConfigManager;
use pocketmine\world\WorldCreationOptions;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\generator\GeneratorOptions;

class WorldManager {

    public static function init(): void {
        self::loadWorlds("islandData", "island");
        self::loadWorlds("gridLandData", "gridland");
        self::loadWorlds("skyBlockData", "skyblock");
    }

    private static function loadWorlds(string $dataFile, string $type): void {
        $config = ConfigManager::getConfig($dataFile);
        $worlds = $config->getAll();

        foreach ($worlds as $worldName => $data) {
            if (!Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) {
                Server::getInstance()->getWorldManager()->loadWorld($worldName);
            }
        }
    }

    public static function createWorld(string $generatorName, string $worldName): bool {
    $server = Server::getInstance();
    $generatorEntry = GeneratorManager::getInstance()->getGenerator($generatorName);

    if ($generatorEntry === null) {
        return false;
    }

    // ✅ GeneratorEntry에서 클래스 이름을 가져옴
    $generatorClass = $generatorEntry->getGeneratorClass(); 

    // ✅ 최신 PocketMine-MP 5.x 방식으로 수정
    $options = [
        "preset" => "island"  // 생성기 설정에 필요한 옵션을 배열 형태로 정의
    ];

    // 배열을 JSON 문자열로 변환
    $optionsString = json_encode($options);

    $worldCreationOptions = new WorldCreationOptions();
    $worldCreationOptions->setGeneratorClass($generatorClass); // 클래스 이름을 문자열로 전달
    $worldCreationOptions->setGeneratorOptions($optionsString); // JSON 문자열로 전달

    return $server->getWorldManager()->generateWorld($worldName, $worldCreationOptions);
}
    
    public static function teleportToWorld(Player $player, string $worldName): bool {
    $worldManager = Server::getInstance()->getWorldManager();

    // ✅ 월드가 로드되지 않은 경우 로드
    if (!$worldManager->isWorldLoaded($worldName)) {
        if (!$worldManager->loadWorld($worldName)) {
            $player->sendMessage("§c월드 로드에 실패했습니다.");
            return false;
        }
    }

    $world = $worldManager->getWorldByName($worldName);

    // ✅ 스폰 청크 강제 생성 (0, 0)
    $world->loadChunk(0, 0);

    // ✅ 청크 상태 확인
    $isGenerated = $world->isChunkGenerated(0, 0);
    $isPopulated = $world->isChunkPopulated(0, 0);

    // ✅ 디버깅 정보 출력
    $player->sendMessage("§b[디버그] 청크 상태:");
    $player->sendMessage("§b- isChunkGenerated: " . ($isGenerated ? "true" : "false"));
    $player->sendMessage("§b- isChunkPopulated: " . ($isPopulated ? "true" : "false"));

    // ✅ 청크가 생성되지 않았다면 원인 분석 및 오류 메시지 출력
    if (!$isGenerated) {
        $player->sendMessage("§c청크가 생성되지 않았습니다. 생성기 설정을 확인하세요.");
        return false;
    }

    if (!$isPopulated) {
        $player->sendMessage("§c청크가 완전히 생성되지 않았습니다. 생성기 populateChunk() 확인 필요.");
        return false;
    }

    // ✅ 스폰 위치가 안전한지 확인하고 이동
    $player->teleport($world->getSafeSpawn());
    $player->sendMessage("§a섬으로 이동했습니다!");
    return true;
}

    public static function deleteWorld(string $worldName): bool {
        if (Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) {
            Server::getInstance()->getWorldManager()->unloadWorld(
                Server::getInstance()->getWorldManager()->getWorldByName($worldName)
            );
        }
        $worldPath = Server::getInstance()->getDataPath() . "worlds/" . $worldName;
        if (is_dir($worldPath)) {
            self::deleteDirectory($worldPath);
            return true;
        }
        return false;
    }

    private static function deleteDirectory(string $dir): void {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                $path = $dir . "/" . $file;
                is_dir($path) ? self::deleteDirectory($path) : unlink($path);
            }
        }
        rmdir($dir);
    }
}
