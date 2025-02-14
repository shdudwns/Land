<?php

namespace HybridIslandPlugin\world;

use pocketmine\player\Player;
use pocketmine\math\Vector3;
use HybridIslandPlugin\config\SkyBlockConfig;
use HybridIslandPlugin\world\WorldManager;

class SkyBlockManager {

    // ✅ 플레이어가 SkyBlock을 가지고 있는지 확인
    public static function hasSkyBlock(Player $player): bool {
        $data = SkyBlockConfig::getIsland($player->getName());
        return $data !== null;
    }

    // ✅ SkyBlock 생성 (16x16 크기)
    public static function createSkyBlock(Player $player): void {
        if (self::hasSkyBlock($player)) {
            $player->sendMessage("§c이미 SkyBlock이 존재합니다.");
            return;
        }

        $blockNumber = count(SkyBlockConfig::getAllIslands()) + 1;
        $worldName = "skyblock_" . $blockNumber;
        $blockData = [
            "owner" => $player->getName(),
            "number" => $blockNumber,
            "world" => $worldName,
            "location" => [
                "x" => 8,   // 16x16 중앙 스폰
                "y" => 65,
                "z" => 8
            ]
        ];

        // ✅ World 생성
        if (WorldManager::createWorld("skyblock", $worldName)) {
            SkyBlockConfig::setIsland($player->getName(), $blockData);
            $player->sendMessage("§aSkyBlock이 성공적으로 생성되었습니다!");
            self::teleportToSkyBlock($player); // ✅ 생성 후 바로 이동
        } else {
            $player->sendMessage("§cSkyBlock 생성에 실패했습니다.");
        }
    }

    // ✅ SkyBlock 삭제
    public static function deleteSkyBlock(Player $player): void {
        if (!self::hasSkyBlock($player)) {
            $player->sendMessage("§c삭제할 SkyBlock이 없습니다.");
            return;
        }

        $data = SkyBlockConfig::getIsland($player->getName());
        $worldName = $data["world"];

        if (WorldManager::deleteWorld($worldName)) {
            SkyBlockConfig::deleteIsland($player->getName());
            $player->sendMessage("§aSkyBlock이 삭제되었습니다.");
        } else {
            $player->sendMessage("§cSkyBlock 삭제에 실패했습니다.");
        }
    }

    // ✅ SkyBlock로 이동
    public static function teleportToSkyBlock(Player $player): void {
        if (!self::hasSkyBlock($player)) {
            $player->sendMessage("§cSkyBlock이 없습니다.");
            return;
        }

        $data = SkyBlockConfig::getIsland($player->getName());
        $worldName = $data["world"];

        if (WorldManager::teleportToWorld($player, $worldName)) {
            $player->sendMessage("§aSkyBlock으로 이동했습니다.");
        } else {
            $player->sendMessage("§cSkyBlock으로 이동할 수 없습니다.");
        }
    }

    // ✅ SkyBlock 정보 보기
    public static function getSkyBlockInfo(Player $player): string {
        $data = SkyBlockConfig::getIsland($player->getName());
        if ($data) {
            return "§bSkyBlock 번호: §f" . $data["number"] . "\n§b소유자: §f" . $data["owner"];
        }
        return "§cSkyBlock 정보가 없습니다.";
    }

    public static function isInsideGridLand(Vector3 $pos): bool {
        $allLands = GridLandConfig::getAllIslands();
        
        foreach ($allLands as $landData) {
            $location = $landData['location'];
            if (
                $pos->x >= $location['startX'] && $pos->x <= $location['endX'] &&
                $pos->z >= $location['startZ'] && $pos->z <= $location['endZ']
            ) {
                return true;
            }
        }
        return false;
    }

    // ✅ GridLand 위치로 소유자 정보 확인
    public static function getGridLandByPosition(Vector3 $pos): ?array {
        $allLands = GridLandConfig::getAllIslands();
        
        foreach ($allLands as $landData) {
            $location = $landData['location'];
            if (
                $pos->x >= $location['startX'] && $pos->x <= $location['endX'] &&
                $pos->z >= $location['startZ'] && $pos->z <= $location['endZ']
            ) {
                return $landData;
            }
        }
        return null;
    }

    public static function isInsideSkyBlock(Vector3 $pos): bool {
        $allIslands = SkyBlockConfig::getAllIslands();

        foreach ($allIslands as $island) {
            $worldName = $island["world"];
            $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);

            if ($world !== null && $pos->world->getFolderName() === $worldName) {
                return true;
            }
        }
        return false;
    }
}
