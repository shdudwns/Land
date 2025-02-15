<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use HybridIslandPlugin\world\GridLandManager;
use HybridIslandPlugin\command\utils\SubCommandMap;

class GridLandCommand extends Command {

    private SubCommandMap $subCommandMap;

    public function __construct() {
        parent::__construct("gridland", "GridLand 관리 명령어", "/gridland <create|delete|home|info>");
        $this->setPermission("hybridislandplugin.command.gridland");

        // ✅ SubCommandMap 연동
        $this->subCommandMap = new SubCommandMap();
        $this->subCommandMap->registerSubCommand("create", function(Player $sender) {
            GridLandManager::createGridLand($sender);
        }, "GridLand 생성", "/gridland create");

        $this->subCommandMap->registerSubCommand("delete", function(Player $sender) {
            GridLandManager::deleteGridLand($sender);
        }, "GridLand 삭제", "/gridland delete");

        $this->subCommandMap->registerSubCommand("home", function(Player $sender) {
            GridLandManager::teleportToGridLand($sender);
        }, "GridLand로 이동", "/gridland home");

        $this->subCommandMap->registerSubCommand("info", function(Player $sender) {
            $info = GridLandManager::getGridLandInfo($sender);
            $sender->sendMessage($info);
        }, "GridLand 정보 보기", "/gridland info");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("게임 내에서만 사용 가능합니다.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage("§a사용 가능한 명령어:");
            foreach ($this->subCommandMap->getAllInfo() as $name => $info) {
                $sender->sendMessage("§e/gridland $name §7- " . $info["description"]);
            }
            return false;
        }

        $subCommand = strtolower($args[0]);
        if ($this->subCommandMap->executeSubCommand($subCommand, $sender)) {
            return true;
        }

        $sender->sendMessage("§c잘못된 명령어입니다.");
        return false;
    }

    // ✅ 자동완성 미리보기 추가
    public function getOverloads(): array {
        return $this->subCommandMap->getAllNames();
    }
}
