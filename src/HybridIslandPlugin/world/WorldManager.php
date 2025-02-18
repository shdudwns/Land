<?php

namespace HybridIslandPlugin\world;

use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\Server;
use HybridIslandPlugin\config\ConfigManager;
use pocketmine\world\WorldCreationOptions;
use pocketmine\world\generator\GeneratorManager;
use HybridIslandPlugin\world\WorldCreationOptionsManager;
use pocketmine\math\Vector3;

class WorldManager {

    public static function init(): void {
        self::loadWorlds("islandData");
        self::loadWorlds("gridLandData");
        self::loadWorlds("skyBlockData");
    }

    private static function loadWorlds(string $dataFile): void {
        $config = ConfigManager::getConfig($dataFile);
        $worlds = $config->getAll();

        foreach ($worlds as $worldName => $data) {
            $worldManager = Server::getInstance()->getWorldManager();
            if (!$worldManager->isWorldLoaded($worldName)) {
                $worldManager->loadWorld($worldName);
            }
        }
    }

   public static function createWorld(string $type, string $worldName): bool {
    $server = Server::getInstance();
    $worldManager = $server->getWorldManager();

    if ($worldManager->isWorldGenerated($worldName)) {
        return false; // 이미 존재하는 월드
    }

    try {
        $options = WorldCreationOptionsManager::getOptions($type);
        $worldManager->generateWorld($worldName, $options);
        
        // ✅ 월드 로드 후 스폰 위치 설정
        if ($worldManager->loadWorld($worldName)) {
            $world = $worldManager->getWorldByName($worldName);
            if ($world !== null) {
                switch (strtolower($type)) {
                    case "island":
                        $world->setSpawnLocation(new Vector3(8, 65, 8));
                        break;
                    case "gridland":
                        $world->setSpawnLocation(new Vector3(0, 65, 0));
                        break;
                    case "skyblock":
                        $world->setSpawnLocation(new Vector3(8, 65, 8));
                        break;
                }
            }
        }
        return true;
    } catch (\Exception $e) {
        return false;
    }
}


    public static function teleportToWorld(Player $player, string $worldName): bool {
        $worldManager = Server::getInstance()->getWorldManager();

        if (!$worldManager->isWorldLoaded($worldName)) {
            if (!$worldManager->loadWorld($worldName)) {
                $player->sendMessage("§c월드 로드에 실패했습니다.");
                return false;
            }
        }

        $world = $worldManager->getWorldByName($worldName);
        if ($world === null) {
            $player->sendMessage("§c월드를 찾을 수 없습니다.");
            return false;
        }

        // ✅ 청크 로드 요청 (비동기 처리 가능)
        $chunkX = 0;
        $chunkZ = 0;
        $world->loadChunk($chunkX, $chunkZ);
        $world->orderChunkPopulation($chunkX, $chunkZ);

        if (!$world->isChunkGenerated($chunkX, $chunkZ) || !$world->isChunkPopulated($chunkX, $chunkZ)) {
            $player->sendMessage("§c청크가 완전히 생성되지 않았습니다.");
            return false;
        }

        $player->teleport($world->getSafeSpawn());
        $player->sendMessage("§a섬으로 이동했습니다!");
        return true;
    }

    public static function deleteWorld(string $worldName): bool {
        $worldManager = Server::getInstance()->getWorldManager();

        if ($worldManager->isWorldLoaded($worldName)) {
            $world = $worldManager->getWorldByName($worldName);
            if ($world !== null) {
                $worldManager->unloadWorld($world);
            }
        }

        $worldPath = Server::getInstance()->getDataPath() . "worlds/" . $worldName;
        if (is_dir($worldPath)) {
            self::deleteDirectory($worldPath);
            return true;
        }
        return false;
    }

    private static function deleteDirectory(string $dir): void {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                self::deleteDirectory($path);
            } else {
                @unlink($path); // 파일 삭제 오류 방지
            }
        }
        rmdir($dir);
    }
}
