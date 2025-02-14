<?php

namespace HybridIslandPlugin\world;

use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\world\WorldCreationOptions;
use pocketmine\Server;
use HybridIslandPlugin\config\ConfigManager;
use HybridIslandPlugin\generator\IslandGenerator;
use HybridIslandPlugin\generator\GridLandGenerator;
use HybridIslandPlugin\generator\SkyBlockGenerator;

class WorldManager {

    public static function init(): void {
        // ✅ 기존 데이터 로드 및 월드 초기화
        self::loadWorlds("islandData", IslandGenerator::class);
        self::loadWorlds("gridLandData", GridLandGenerator::class);
        self::loadWorlds("skyBlockData", SkyBlockGenerator::class);
    }

    private static function loadWorlds(string $dataFile, string $generatorClass): void {
        $config = ConfigManager::getConfig($dataFile);
        $worlds = $config->getAll();

        foreach ($worlds as $worldName => $data) {
            if (!Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) {
                Server::getInstance()->getWorldManager()->loadWorld($worldName);
            }
        }
    }

    public static function createWorld(string $type, string $worldName): bool {
        $generatorClass = match ($type) {
            "island" => IslandGenerator::class,
            "gridland" => GridLandGenerator::class,
            "skyblock" => SkyBlockGenerator::class,
            default => null,
        };

        if ($generatorClass === null) return false;

        if (Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) return false;

        $options = new WorldCreationOptions();
        $options->setGeneratorClass($generatorClass);
        Server::getInstance()->getWorldManager()->generateWorld($worldName, $options);

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
