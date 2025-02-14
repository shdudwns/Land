<?php

namespace HybridIslandPlugin\world;

use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;
use pocketmine\Server;
use HybridIslandPlugin\config\GridLandConfig;
use HybridIslandPlugin\generator\GridLandGenerator;

class GridLandManager {

    // ✅ GridLand 초기화
    public static function init(): void {
        // Config 초기화
        GridLandConfig::init();
        self::loadGridLandWorld();
    }

    // ✅ GridLand용 월드 생성 및 로드
    public static function loadGridLandWorld(): void {
        $worldName = "gridland_world";
        $worldManager = Server::getInstance()->getWorldManager();

        // 월드가 로드되지 않은 경우 생성 및 로드
        if (!$worldManager->isWorldLoaded($worldName)) {
            if (!file_exists(Server::getInstance()->getDataPath() . "worlds/$worldName")) {
                $options = new WorldCreationOptions();
                $options->setGeneratorClass(GridLandGenerator::class);
                $options->setSpawnPosition(new Vector3(0, 65, 0));
                $worldManager->generateWorld($worldName, $options);
            }
            $worldManager->loadWorld($worldName);
        }
    }

    // ✅ 플레이어가 GridLand를 가지고 있는지 확인
    public static function hasGridLand(Player $player): bool {
        $data = GridLandConfig::getIsland($player->getName());
        return $data !== null;
    }

    // ✅ GridLand 생성 (32x32 크기)
    public static function createGridLand(Player $player): void {
        if (self::hasGridLand($player)) {
            $player->sendMessage("§c이미 GridLand가 존재합니다.");
            return;
        }

        $landNumber = count(GridLandConfig::getAllIslands()) + 1;
        $startX = ($landNumber % 10) * 50;   // 가로로 10개 배치
        $startZ = (int)($landNumber / 10) * 50;
        $endX = $startX + 31;
        $endZ = $startZ + 31;

        $landData = [
            "owner" => $player->getName(),
            "number" => $landNumber,
            "location" => [
                "startX" => $startX,
                "endX" => $endX,
                "startZ" => $startZ,
                "endZ" => $endZ
            ]
        ];

        GridLandConfig::setIsland($player->getName(), $landData);
        $player->sendMessage("§aGridLand가 성공적으로 생성되었습니다!");
        self::teleportToGridLand($player);
    }

    // ✅ GridLand 삭제
    public static function deleteGridLand(Player $player): void {
        if (!self::hasGridLand($player)) {
            $player->sendMessage("§c삭제할 GridLand가 없습니다.");
            return;
        }

        GridLandConfig::deleteIsland($player->getName());
        $player->sendMessage("§aGridLand가 삭제되었습니다.");
    }

    // ✅ GridLand로 이동
    public static function teleportToGridLand(Player $player): void {
        if (!self::hasGridLand($player)) {
            $player->sendMessage("§cGridLand가 없습니다.");
            return;
        }

        $data = GridLandConfig::getIsland($player->getName());
        $location = $data["location"];
        $spawnX = ($location["startX"] + $location["endX"]) / 2;
        $spawnZ = ($location["startZ"] + $location["endZ"]) / 2;

        $world = Server::getInstance()->getWorldManager()->getWorldByName("gridland_world");
        $player->teleport(new Vector3($spawnX, 65, $spawnZ));
        $player->sendMessage("§aGridLand로 이동했습니다.");
    }

    // ✅ GridLand 정보 보기
    public static function getGridLandInfo(Player $player): string {
        $data = GridLandConfig::getIsland($player->getName());
        if ($data) {
            return "§bGridLand 번호: §f" . $data["number"] . "\n§b소유자: §f" . $data["owner"];
        }
        return "§cGridLand 정보가 없습니다.";
    }
}
