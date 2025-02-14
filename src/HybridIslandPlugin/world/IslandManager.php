<?php

namespace HybridIslandPlugin\world;

use pocketmine\player\Player;
use pocketmine\math\Vector3;
use HybridIslandPlugin\config\IslandConfig;
use HybridIslandPlugin\world\WorldManager;

class IslandManager {

    // ✅ 플레이어가 섬을 가지고 있는지 확인
    public static function hasIsland(Player $player): bool {
        $data = IslandConfig::getIsland($player->getName());
        return $data !== null;
    }

    // ✅ 섬 생성
    public static function createIsland(Player $player): void {
        if (self::hasIsland($player)) {
            $player->sendMessage("§c이미 섬이 존재합니다.");
            return;
        }

        $islandNumber = count(IslandConfig::getAllIslands()) + 1;
        $worldName = "island_" . $islandNumber;
        $islandData = [
            "owner" => $player->getName(),
            "number" => $islandNumber,
            "world" => $worldName,
            "location" => [
                "x" => 8,
                "y" => 65,
                "z" => 8
            ]
        ];

        // ✅ World 생성
        if (WorldManager::createWorld("island", $worldName)) {
            IslandConfig::setIsland($player->getName(), $islandData);
            $player->sendMessage("§a섬이 성공적으로 생성되었습니다!");
            self::teleportToIsland($player); // ✅ 생성 후 바로 이동
        } else {
            $player->sendMessage("§c섬 생성에 실패했습니다.");
        }
    }

    public static function isInsideIsland(Player $player, Vector3 $pos): bool {
        $data = IslandConfig::getIsland($player->getName());
        if ($data) {
            $location = $data["location"];
            $size = $data["size"] ?? 100; // 섬 크기 기본값 100

            $startX = $location["x"] - ($size / 2);
            $endX = $location["x"] + ($size / 2);
            $startZ = $location["z"] - ($size / 2);
            $endZ = $location["z"] + ($size / 2);

            return (
                $pos->getX() >= $startX && $pos->getX() <= $endX &&
                $pos->getZ() >= $startZ && $pos->getZ() <= $endZ
            );
        }
        return false;
    }
        
    // ✅ 섬 삭제
    public static function deleteIsland(Player $player): void {
        if (!self::hasIsland($player)) {
            $player->sendMessage("§c삭제할 섬이 없습니다.");
            return;
        }

        $data = IslandConfig::getIsland($player->getName());
        $worldName = $data["world"];

        if (WorldManager::deleteWorld($worldName)) {
            IslandConfig::deleteIsland($player->getName());
            $player->sendMessage("§a섬이 삭제되었습니다.");
        } else {
            $player->sendMessage("§c섬 삭제에 실패했습니다.");
        }
    }

    // ✅ 섬으로 이동
    public static function teleportToIsland(Player $player): void {
        if (!self::hasIsland($player)) {
            $player->sendMessage("§c섬이 없습니다.");
            return;
        }

        $data = IslandConfig::getIsland($player->getName());
        $worldName = $data["world"];

        if (WorldManager::teleportToWorld($player, $worldName)) {
            $player->sendMessage("§a섬으로 이동했습니다.");
        } else {
            $player->sendMessage("§c섬으로 이동할 수 없습니다.");
        }
    }

    // ✅ 섬 정보 보기
    public static function getIslandInfo(Player $player): string {
        $data = IslandConfig::getIsland($player->getName());
        if ($data) {
            return "§b섬 번호: §f" . $data["number"] . "\n§b소유자: §f" . $data["owner"];
        }
        return "§c섬 정보가 없습니다.";
    }
}
