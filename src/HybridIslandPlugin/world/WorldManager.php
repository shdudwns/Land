<?php

namespace HybridIslandPlugin\world;

use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\Server;
use HybridIslandPlugin\config\ConfigManager;

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

    public static function createWorld(string $type, string $worldName): bool {
        if (Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) return false;

        // ✅ WorldCreationOptionsManager 연동
        try {
            $options = WorldCreationOptionsManager::getOptions($type);
            Server::getInstance()->getWorldManager()->generateWorld($worldName, $options);

            // ✅ World 생성 후에 스폰 위치 재확인
            $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);
            if ($world instanceof World) {
                $world->setSpawnLocation($options->getSpawnLocation());
            }
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    public static function teleportToWorld(Player $player, string $worldName): bool {
        if (!Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) {
            if (!Server::getInstance()->getWorldManager()->loadWorld($worldName)) {
                return false;
            }
        }

        $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);
        $player->teleport($world->getSafeSpawn());
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
