<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use HybridIslandPlugin\world\IslandManager;
use HybridIslandPlugin\config\IslandConfig;
use HybridIslandPlugin\util\MessageUtil;

class IslandCommand extends Command {

    public function __construct() {
        parent::__construct("island", "섬 관리 명령어", "/island <create|delete|home|info|expand>");
        $this->setPermission("hybridislandplugin.command.island");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("게임 내에서만 사용 가능합니다.");
            return false;
        }

        if (!isset($args[0])) {
            $sender->sendMessage("/island <create|delete|home|info|expand>");
            return false;
        }

        switch (strtolower($args[0])) {
            case "create":
                // ✅ 섬 구매 및 생성
                if (IslandManager::hasIsland($sender)) {
                    $sender->sendMessage("이미 섬을 소유하고 있습니다.");
                    return false;
                }

                $price = 20000; // ✅ 섬 가격 설정
                if ($sender->getXpManager()->getXpLevel() < $price) {
                    $sender->sendMessage("경험치가 부족합니다. 필요한 경험치: $price");
                    return false;
                }

                $sender->getXpManager()->setXpLevel($sender->getXpManager()->getXpLevel() - $price);
                IslandManager::createIsland($sender);
                $sender->sendMessage("섬이 성공적으로 생성되었습니다!");
                break;

            case "delete":
                IslandManager::deleteIsland($sender);
                $sender->sendMessage("섬이 삭제되었습니다.");
                break;

            case "home":
                IslandManager::teleportToIsland($sender);
                break;

            case "info":
                $info = IslandManager::getIslandInfo($sender);
                $sender->sendMessage($info);
                break;

            case "expand":
                $result = IslandManager::expandIsland($sender);
                $sender->sendMessage($result);
                break;

            default:
                $sender->sendMessage("/island <create|delete|home|info|expand>");
                break;
        }
        return true;
    }
}
