<?php

namespace HybridIslandPlugin\world;

use pocketmine\player\Player;
use HybridIslandPlugin\config\IslandConfig;

class IslandManager {

    public static function hasIsland(Player $player): bool {
        $data = IslandConfig::getIsland($player->getName());
        return $data !== null;
    }

    public static function createIsland(Player $player): void {
        $islandNumber = count(IslandConfig::getAllIslands()) + 1;
        $islandData = [
            "owner" => $player->getName(),
            "number" => $islandNumber,
            "location" => [
                "x" => $islandNumber * 200,
                "y" => 100,
                "z" => $islandNumber * 200
            ]
        ];
        IslandConfig::setIsland($player->getName(), $islandData);
    }

    public static function deleteIsland(Player $player): void {
        IslandConfig::deleteIsland($player->getName());
    }

    public static function teleportToIsland(Player $player): void {
        $data = IslandConfig::getIsland($player->getName());
        if ($data) {
            $location = $data["location"];
            $player->teleport(new Vector3($location["x"], $location["y"], $location["z"]));
        } else {
            $player->sendMessage("섬이 없습니다.");
        }
    }

    public static function getIslandInfo(Player $player): string {
        $data = IslandConfig::getIsland($player->getName());
        if ($data) {
            return "섬 번호: " . $data["number"] . "\n소유자: " . $data["owner"];
        }
        return "섬 정보가 없습니다.";
    }

    public static function expandIsland(Player $player): string {
        $data = IslandConfig::getIsland($player->getName());
        if ($data) {
            $data["size"] = ($data["size"] ?? 100) + 50;
            IslandConfig::setIsland($player->getName(), $data);
            return "섬이 확장되었습니다!";
        }
        return "섬 정보가 없습니다.";
    }
}
