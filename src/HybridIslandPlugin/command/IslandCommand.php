<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use HybridIslandPlugin\world\IslandManager;
use HybridIslandPlugin\Main;
use HybridIslandPlugin\command\utils\SubCommandMap;

class IslandCommand extends Command {

    private SubCommandMap $subCommandMap;

    public function __construct() {
        parent::__construct("island", "섬 관리 명령어", "/island <create|delete|home|info>", ["isl"]);
        $this->setPermission("hybridislandplugin.command.island");

        // ✅ SubCommandMap 연동
        $this->subCommandMap = new SubCommandMap();
        $this->subCommandMap->registerSubCommand("create", function(Player $sender) {
            IslandManager::createIsland($sender);
        }, "섬 생성", "/island create");

        $this->subCommandMap->registerSubCommand("delete", function(Player $sender) {
            IslandManager::deleteIsland($sender);
        }, "섬 삭제", "/island delete");

        $this->subCommandMap->registerSubCommand("home", function(Player $sender) {
            IslandManager::teleportToIsland($sender);
        }, "섬으로 이동", "/island home");

        $this->subCommandMap->registerSubCommand("info", function(Player $sender) {
            $info = IslandManager::getIslandInfo($sender);
            $sender->sendMessage($info);
        }, "섬 정보 보기", "/island info");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§c플레이어만 사용 가능합니다.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage("§a사용 가능한 명령어:");
            foreach ($this->subCommandMap->getAllInfo() as $name => $info) {
                $sender->sendMessage("§e/island $name §7- " . $info["description"]);
            }
            return false;
        }

        $subCommand = strtolower($args[0]);
        if (in_array($subCommand, $this->subCommandMap->getAllNames())) {
            return $this->subCommandMap->executeSubCommand($subCommand, $sender);
        }

        $sender->sendMessage("§c잘못된 명령어입니다.");
        return false;
    }

    // ✅ 자동완성 미리보기
    public function getOverloads(): array {
        $subCommands = [];
        foreach ($this->subCommandMap->getAllNames() as $name) {
            $subCommands[] = [
                "name" => $name,
                "type" => "string",
                "optional" => true
            ];
        }
        return $subCommands;
    }
}
