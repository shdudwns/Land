<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;
use HybridIslandPlugin\world\IslandManager;
use HybridIslandPlugin\Main;

class IslandCommand extends Command implements PluginIdentifiableCommand {

    public function __construct() {
        parent::__construct("island", "섬 관리 명령어", "/island <create|delete|home|info>", ["isl"]);
        $this->setPermission("hybridislandplugin.command.island");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§c플레이어만 사용 가능합니다.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage("§a사용 가능한 명령어:");
            $sender->sendMessage("§e/island create §f- 섬 생성");
            $sender->sendMessage("§e/island delete §f- 섬 삭제");
            $sender->sendMessage("§e/island home §f- 섬으로 이동");
            $sender->sendMessage("§e/island info §f- 섬 정보 보기");
            return false;
        }

        switch (strtolower($args[0])) {
            case "create":
                IslandManager::createIsland($sender);
                break;

            case "delete":
                IslandManager::deleteIsland($sender);
                break;

            case "home":
                IslandManager::teleportToIsland($sender);
                break;

            case "info":
                $info = IslandManager::getIslandInfo($sender);
                $sender->sendMessage($info);
                break;

            default:
                $sender->sendMessage("§c잘못된 명령어입니다.");
                break;
        }
        return true;
    }

    public function getPlugin(): Plugin {
        return Main::getInstance();
    }
}
