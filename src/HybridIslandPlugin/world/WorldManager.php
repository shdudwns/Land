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
    $generatorClass = GeneratorManager::getInstance()->getGenerator($generatorName);

    if ($generatorClass === null) {
        return false;
    }

    $options = new GeneratorOptions([]); // ✅ GeneratorOptions 객체화
    $worldCreationOptions = new WorldCreationOptions()->setGeneratorClass($generatorClass)->setGeneratorOptions($options); // ✅ 객체 전달

    return $server->getWorldManager()->generateWorld($worldName, $worldCreationOptions);
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
