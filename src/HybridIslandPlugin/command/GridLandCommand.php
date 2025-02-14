<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use HybridIslandPlugin\world\GridLandManager;
use HybridIslandPlugin\config\GridLandConfig;

class GridLandCommand extends Command {

    public function __construct() {
        parent::__construct("gridland", "GridLand 관리 명령어", "/gridland <create|delete|home|info>");
        $this->setPermission("hybridislandplugin.command.gridland");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("게임 내에서만 사용 가능합니다.");
            return false;
        }

        if (!isset($args[0])) {
            $sender->sendMessage("/gridland <create|delete|home|info>");
            return false;
        }

        switch (strtolower($args[0])) {
            case "create":
                GridLandManager::createGridLand($sender);
                $sender->sendMessage("GridLand가 생성되었습니다!");
                break;

            case "delete":
                GridLandManager::deleteGridLand($sender);
                $sender->sendMessage("GridLand가 삭제되었습니다.");
                break;

            case "home":
                GridLandManager::teleportToGridLand($sender);
                break;

            case "info":
                $info = GridLandManager::getGridLandInfo($sender);
                $sender->sendMessage($info);
                break;

            default:
                $sender->sendMessage("/gridland <create|delete|home|info>");
                break;
        }
        return true;
    }
}
